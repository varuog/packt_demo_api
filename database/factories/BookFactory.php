<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Tags\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'packt_id' => fake()->uuid(),
            'title' => fake()->sentence(),
            'isbn' => fake()->isbn13(),
            'publication_date' => fake()->date('Y'),
            'release_year' => fake()->date('Y'),
            'product_type' => fake()->randomElement(['ebook', 'print']),
            'url' => fake()->url(),
            'pages' => fake()->numberBetween(50, 500),
            'description' => fake()->text(),
        ];
    }

    public function configure() {
        return $this->afterCreating(function($book) {
            $language = Tag::where('type', Book::CATEGORY_TYPE_LANGUAGE)->inRandomOrder()->first();
            $concept = Tag::where('type', Book::CATEGORY_TYPE_CONCEPT)->inRandomOrder()->first();
            $category = Tag::where('type', Book::CATEGORY_TYPE_CATEGORY)->inRandomOrder()->first();
            //dd($language);
            $book->attachTag($language);
            $book->attachTag($concept);
            $book->attachTag($category);

        });
    }
}