<?php

namespace App\Service;

use App\Models\Author;

class AuthorService
{
    /**
     * Create bulk authors
     *
     * @param  array  $authors array of author data from API
     * @return array Author array of author model
     */
    public function bulkAdd(array $authors)
    {
        $authorData = [];

        foreach ($authors as $author) {
            $authorObjs[] = Author::updateOrCreate([
                'name' => $author,
            ]);
        }

        return $authorObjs;
    }
}
