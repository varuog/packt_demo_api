<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Book\BookDetailsResource;
use App\Http\Resources\Book\BookListingResource as BookBookListingResource;
use App\Http\Resources\Book\BookListThumbResource;
use App\Http\Resources\BookListingResource;
use App\Service\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter = request()->get('filter', []);
        $sort = request()->get('sort', []);
        $perPage = request()->get('perPage', config('packt.perPage'));
        $view = request()->get('view', 'list');

        $books = $this->bookService->fetchAll($filter, $sort, $perPage);

        return response()->json([
            'status' => 200,
            'data'=> ($view == 'list') ? BookBookListingResource::collection($books->items()) 
                : BookListThumbResource::collection($books->items()),
            'error' => [],
            'message' => __('Book List has been loaded sucessfuly'),
            'meta' => [
                'total' => $books->total(),
                'currentTotal' => $books->count(),
                'perPage' => $books->perPage(),
                'currentPage' => $books->currentPage(),
                'lastPage' => $books->lastPage()
            ]

        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = $this->bookService->fetch($id);

        return response()->json([
            'status' => 200,
            'data'=> new BookDetailsResource($book),
            'error' => [],
            'message' => __('Book List has been loaded sucessfuly'),
            'meta' => [
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
