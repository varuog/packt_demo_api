<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('packt_id')->unique();
            $table->string('isbn', 14)->unique();
            $table->string('title')->index();
            $table->string('product_type')->index();
            $table->year('publication_date')->index();
            $table->year('release_year')->index();
            $table->string('url');
            $table->unsignedInteger('pages');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
