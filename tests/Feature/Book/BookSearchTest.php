<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookSearchTest extends TestCase
{
    use RefreshDatabase;

    // protected $seed = true;

    public function noMatchFoundProvider()
    {
        return [
            [[
                'filter[product_type][]=ebook',
                'filter[release_year][]=2000',
                'filter[publication_date][]=2000',
                'filter[concept][]=Blockchain',
                'filter[language][]=Java',
                'filter[category][]=cli',

            ]],
            [[
                'filter[product_type][]=ebook',
                'filter[release_year][]=2022',
                'filter[publication_date][]=2000',
                'filter[concept][]=Blockchain',
                'filter[language][]=PHP',
                'filter[category][]=cli',

            ]],
            [[
                'filter[product_type][]=pdf',
                'filter[release_year][]=2022',
                'filter[publication_date][]=2022',
                'filter[concept][]=Web',
                'filter[language][]=Java',
                'filter[category][]=cli',

            ]],
        ];
    }

    /**
     * @dataProvider noMatchFoundProvider
     */
    public function test_search_product_no_match($filterParam)
    {
        Book::factory(5)
            ->state(
                [
                    'product_type' => 'ebook',
                    'release_year' => 2022,
                    'publication_date' => 2021,

                ]
            )
            ->create()
            ->each(function ($book) {
                $book->attachTags(['Desktop Programming'], Book::CATEGORY_TYPE_CONCEPT);
                $book->attachTags(['PHP'], Book::CATEGORY_TYPE_LANGUAGE);
                $book->attachTags(['Web'], Book::CATEGORY_TYPE_CATEGORY);
            });

        $queryStr = implode('&', $filterParam);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 0)
                    ->etc();
            });
    }

    public function test_search_category()
    {
        Book::factory(2)
            ->create()
            ->each(function ($book) {
                $book->attachTags(['Programming'], Book::CATEGORY_TYPE_CATEGORY);
            });
        Book::factory(5)
            ->create()
            ->each(function ($book) {
                $book->attachTags(['Blockchain'], Book::CATEGORY_TYPE_CATEGORY);
            });

        $queryStr = implode('&', [
            'filter[category][]=Programming',
        ]);
        // dd($queryStr);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 2)
                    ->etc();
            });
    }

    public function test_search_language()
    {
        Book::factory(2)
            ->create()
            ->each(function ($book) {
                $book->attachTags(['PHP'], Book::CATEGORY_TYPE_LANGUAGE);
            });
        Book::factory(5)
            ->create()
            ->each(function ($book) {
                $book->attachTags(['JS'], Book::CATEGORY_TYPE_LANGUAGE);
            });

        $queryStr = implode('&', [
            'filter[language][]=PHP',
        ]);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 2)
                    ->etc();
            });
    }

    public function test_search_concept()
    {
        Book::factory(2)
            ->create()
            ->each(function ($book) {
                $book->attachTags(['Web Development'], Book::CATEGORY_TYPE_CONCEPT);
            });
        Book::factory(5)
            ->create()
            ->each(function ($book) {
                $book->attachTags(['Desktop Programming'], Book::CATEGORY_TYPE_CONCEPT);
            });

        $queryStr = implode('&', [
            'filter[concept][]=Web Development',
        ]);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 2)
                    ->etc();
            });
    }

    public function test_search_publication_date()
    {
        Book::factory(2)
            ->state(
                [
                    'publication_date' => 2022,
                ]
            )
            ->create();

        Book::factory(5)
            ->state(
                [
                    'publication_date' => 2021,
                ]
            )
            ->create();

        $queryStr = implode('&', [
            'filter[publish_year][]=2022',
        ]);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 2)
                    ->etc();
            });
    }

    public function test_search_release_date()
    {
        Book::factory(2)
            ->state(
                [
                    'release_year' => 2022,
                ]
            )
            ->create();

        Book::factory(5)
            ->state(
                [
                    'release_year' => 2021,
                ]
            )
            ->create();

        $queryStr = implode('&', [
            'filter[release_year][]=2022',
        ]);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 2)
                    ->etc();
            });
    }

    public function test_search_product_type()
    {
        Book::factory(2)
            ->state(
                [
                    'product_type' => 'ebook',
                ]
            )
            ->create();

        Book::factory(5)
            ->state(
                [
                    'product_type' => 'pdf',
                ]
            )
            ->create();

        $queryStr = implode('&', [
            'filter[product_type][]=ebook',
        ]);
        $response = $this->getJson(sprintf('/api/book?%s', $queryStr));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('error', [])
                    ->where('status', 200)
                    ->whereType('data', 'array')
                    ->has('data', 2)
                    ->etc();
            });
    }
}
