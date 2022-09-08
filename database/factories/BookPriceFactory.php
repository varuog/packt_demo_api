<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookPrice>
 */
class BookPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'currency' => fake()->randomElement(config('packt.currency')),
            'type' => fake()->randomElement(config('packt.type')),
            'price' => fake()->randomDigit(),
            'book_id' => Book::factory(),
        ];
    }
}
