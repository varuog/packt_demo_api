<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\BookService;
use Illuminate\Http\Request;

class BookSearchOptionController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $filters = $this->bookService->fetchFilters();

        return response()->json([
            'status' => 200,
            'data' => [
                'filters' => $filters,
            ],
            'error' => [],
            'message' => __('Book filter option has been loaded sucessfuly'),
            'meta' => [
            ],

        ]);
    }
}
