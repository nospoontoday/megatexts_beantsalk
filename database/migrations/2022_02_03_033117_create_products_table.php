<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('isbn_13')->unique();
            $table->string('author');
            $table->string('publisher');
            $table->text('title');
            $table->string('year_published');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('subject')->nullable();
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
        Schema::dropIfExists('products');
    }
}
