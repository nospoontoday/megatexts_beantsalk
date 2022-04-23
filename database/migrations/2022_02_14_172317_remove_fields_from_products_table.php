<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('isbn_13');
            $table->dropColumn('author');
            $table->dropColumn('publisher');
            $table->dropColumn('year_published');
            $table->dropColumn('discount');
            $table->dropColumn('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('isbn_13')->unique();
            $table->string('author');
            $table->string('publisher');
            $table->string('year_published');
            $table->decimal('discount', 10, 2);
            $table->decimal('total', 10, 2);
        });
    }
}
