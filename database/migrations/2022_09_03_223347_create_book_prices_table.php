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
        Schema::create('book_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->index();
            $table->enum('currency', config('packt.currency'));
            $table->enum('type', config('packt.type'));
            $table->unsignedInteger('price')->comment('price will be multiple of 100');
            $table->timestamps();

            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_prices');
    }
};
