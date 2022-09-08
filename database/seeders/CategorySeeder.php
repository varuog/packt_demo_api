<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Avalable languages
        Tag::create(['name' => 'PHP', 'type' => Book::CATEGORY_TYPE_LANGUAGE]);
        Tag::create(['name' => 'JS', 'type' => Book::CATEGORY_TYPE_LANGUAGE]);
        Tag::create(['name' => 'Python', 'type' => Book::CATEGORY_TYPE_LANGUAGE]);
        Tag::create(['name' => 'Java', 'type' => Book::CATEGORY_TYPE_LANGUAGE]);

        // //Available concept
        Tag::create(['name' => 'Web Development', 'type' => Book::CATEGORY_TYPE_CONCEPT]);
        Tag::create(['name' => 'Dev Ops', 'type' => Book::CATEGORY_TYPE_CONCEPT]);
        Tag::create(['name' => 'Ecommerce', 'type' => Book::CATEGORY_TYPE_CONCEPT]);

         // //Available concept
         Tag::create(['name' => 'Web Development', 'type' => Book::CATEGORY_TYPE_CATEGORY]);
         Tag::create(['name' => 'Dev Ops', 'type' => Book::CATEGORY_TYPE_CATEGORY]);
         Tag::create(['name' => 'Ecommerce', 'type' => Book::CATEGORY_TYPE_CATEGORY]);
    }
}
