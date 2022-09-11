<?php

namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    public function books()
    {
        return $this->morphedByMany(Book::class, 'taggable');
    }
}
