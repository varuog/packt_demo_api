<?PHP

namespace App\Service;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookService
{

    const FILTER_TITLE = 'title';
    const FILTER_PRODUCT_TYPE = 'product_type';
    const FILTER_PUBLISH_YEAR = 'publish_year';
    const FILTER_RELEASE_YEAR = 'release_year';
    const FILTER_LANGUAGE = 'language';
    const FILTER_CONCEPT = 'concept';
    const FILTER_CATEGORY = 'category';
    const FILTER_PUBLISH_AUTHOR = 'author';

    const SORT_ORDER_DESC = 'DESC';
    const SORT_ORDER_ASC = 'ASC';

    const SORT_FIELD_PUBLISH_YEAR = 'publish_year';
    const SORT_FIELD_POPULAR = 'popular';

    protected $httpService;
    protected $authorService;


    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Fetch book list along with filters and sorting
     * @param array $filter array key value pait of filters. value can be string or array (for multi selection)   
     *            valid value for key static::FILTER_*
     * @param array $sort array valid key values array static::SORT_FIELD_*  and valid values are 'ASC' and 'DESC    
     * @param array $perPage null|integer if null all data fetched else per page item will be sent as per param
     */
    public function fetchAll(array $filter = [], array $sort = [], $perPage = null)
    {
        $bookQuery = Book::query();
        $bookQuery->with(['authors', 'tags']);
        //dd($filter);
        /*
        * Handles filter
        */
        if (isset($filter[static::FILTER_TITLE])) {
            $bookQuery->where('title', 'like', "%{$filter[static::FILTER_TITLE]}%")
                ->orWhere('description', 'like', "%{$filter[static::FILTER_TITLE]}%");
        }

        if (isset($filter[static::FILTER_PUBLISH_YEAR])) {
            $bookQuery->whereIn('publication_date', $filter[static::FILTER_PUBLISH_YEAR]);
        }

        if (isset($filter[static::FILTER_RELEASE_YEAR])) {
            $bookQuery->whereIn('release_year', $filter[static::FILTER_RELEASE_YEAR]);
        }

        if (isset($filter[static::FILTER_PRODUCT_TYPE])) {
            $bookQuery->whereIn('product_type', $filter[static::FILTER_PRODUCT_TYPE]);
        }

        if (isset($filter[static::FILTER_PUBLISH_AUTHOR])) {
            $bookQuery->whereHas('authors', function ($query) use ($filter) {
                return $query->whereIn('name', $filter[static::FILTER_PUBLISH_AUTHOR]);
            });
        }

        if (isset($filter[static::FILTER_CATEGORY])) {
            $bookQuery->withAnyTags($filter[static::FILTER_CATEGORY], Book::CATEGORY_TYPE_CATEGORY);
        }

        if (isset($filter[static::FILTER_CONCEPT])) {
            $bookQuery->withAnyTags($filter[static::FILTER_CONCEPT], Book::CATEGORY_TYPE_CONCEPT);
        }

        if (isset($filter[static::FILTER_LANGUAGE])) {
            $bookQuery->withAnyTags($filter[static::FILTER_LANGUAGE], Book::CATEGORY_TYPE_LANGUAGE);
        }

        /*
        * Handles sorting
        */
        foreach ($sort as $sortField => $sortOrder) {
            if (in_array($sortField, [static::SORT_FIELD_PUBLISH_YEAR, static::SORT_FIELD_POPULAR])) {
                $bookQuery->orderBy($sortField, $sortOrder);
            }
        }

        if ($perPage) {
            return $bookQuery->paginate($perPage);
        } else {
            return $bookQuery->all();
        }
    }


    /**
     * Used to fetch book details
     * @param $bookId intenger book Id PK
     * @return Book return book model
     */
    public function fetch($bookId)
    {
        $bookQuery = Book::findOrFail($bookId);
        $bookQuery->with('authors', 'tags');

        return $bookQuery->first();
    }


    /**
     * Fetch available filters
     * @return array filter with filter id, displayname, and available value for the filters
     */
    public function fetchFilters()
    {
        $productTypes = Book::select('product_type AS name', DB::raw('COUNT("product_type") as total'))
            ->groupBy('product_type')
            ->get();
        $publicationDates = Book::select('publication_date AS name', DB::raw('COUNT("publication_date") as total'))
            ->groupBy('publication_date')
            ->get();
        $releaseDates = Book::select('release_year AS name', DB::raw('COUNT("release_year") as total'))
            ->groupBy('release_year')
            ->get();

        $categories = Book::withCount('tags');
        // dd($productTypes, $publicationDates, $releaseDates);

        return [
            [
                'filter' =>  static::FILTER_PRODUCT_TYPE,
                'displayName' => __('Product Type'),
                'data' => $productTypes,
            ],
            [
                'filter' =>  static::FILTER_PUBLISH_YEAR,
                'displayName' => __('Publication Date'),
                'data' => $publicationDates,
            ],
            [
                'filter' =>  static::FILTER_RELEASE_YEAR,
                'displayName' => __('Release Year'),
                'data' => $releaseDates,
            ],
            // [
            //     'filter' =>  static::FILTER_LANGUAGE, 
            //     'displayName' => __('Language'),
            //     'data' => [

            //     ]
            // ],
            // [
            //     'filter' =>  static::FILTER_CONCEPT, 
            //     'displayName' => __('Concept'),
            //     'data' => [

            //     ]
            // ],
            // [
            //     'filter' =>  static::FILTER_CATEGORY, 
            //     'displayName' => __('Category'),
            //     'data' => [

            //     ]
            // ],
        ];
    }

    /**
     * Create bulk books
     * @param array $books array of book data from API
     * @return array Author array of book model
     */
    public function bulkAdd(array $bookData)
    {
        // Log::debug($books);

            DB::transaction(function () use ($bookData) {
                $book = Book::updateOrCreate(
                    [
                        'packt_id' => $bookData['id'],
                        'isbn' => $bookData['isbn13'],
                    ]
                    ,
                    [
                        'title' => $bookData['title'],
                        'publication_date' => fake()->date('Y'),
                        'product_type' => fake()->randomElement(['ebook', 'print']),
                        'description' => fake()->text(),
                        'release_year' => fake()->date('Y'),
                        'pages' => fake()->numberBetween(50, 500),
                        'url' => fake()->url(),


                    ],
                );

                $authors = $this->authorService->bulkAdd($bookData['authors']);
                $book->authors()->saveMany($authors);
                if($bookData['categories']) {
                    $categories = is_string($bookData['categories']) ? [$bookData['categories']] : $bookData['categories'];
                    $book->attachTags($categories, Book::CATEGORY_TYPE_CATEGORY);
                }
                if($bookData['concept']) {
                    $concept = is_string($bookData['concept']) ? [$bookData['concept']] : $bookData['concept'];
                    $book->attachTags($concept, Book::CATEGORY_TYPE_CONCEPT);
                }
                if($bookData['language']) {
                    $language = is_string($bookData['language']) ? [$bookData['language']] : $bookData['language'];
                    $book->attachTags($language, Book::CATEGORY_TYPE_LANGUAGE);
                }
                
            });
        
    }
}
