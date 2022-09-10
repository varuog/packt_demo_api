<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorDetailsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookListThumbResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->title,
            'pages' => $this->pages,
            'publication_date' => $this->publication_date,
            'release_year' => $this->release_year,
            'authors' => AuthorDetailsResource::collection($this->authors)
        ];
    }
}
