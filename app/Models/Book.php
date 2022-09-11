<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Book extends Model
{
    use HasFactory, HasTags;

    const CATEGORY_TYPE_LANGUAGE = 'language';

    const CATEGORY_TYPE_CONCEPT = 'concept';

    const CATEGORY_TYPE_CATEGORY = 'category';

    protected $guarded = [];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_books', 'book_id', 'author_id');
    }

    public function prices()
    {
        return $this->hasMany(BookPrice::class, 'book_id');
    }
}
