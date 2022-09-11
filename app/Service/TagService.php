<?PHP

namespace App\Service;

use App\Models\Author;
use App\Models\Book;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TagService
{

    /**
     * Find and group by count book
     * @param array $type enum string from Book::FILTER_*
     * @return array Author array of author model
     */
    public function tagListCountByType($type) {
        $tagData = Tag::select("name", 'slug')->withCount('books as total')
        ->where('type', $type)
        ->get()
        ->map(function ($tag){
            // dd($tag);
            return [
                'name' => $tag->name,
                'total' =>  $tag->total
            ];
        });
        return $tagData;
    }
}
