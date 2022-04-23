<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('editor_id');
            $table->foreign('editor_id')->references('id')->on('editors');
            $table->unsignedBigInteger('publisher_id');
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->unsignedBigInteger('platform_id');
            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->unsignedBigInteger('access_model_id');
            $table->foreign('access_model_id')->references('id')->on('access_models');
            $table->string('e_issn')->unique();
            $table->string('frequency');
            $table->string('subscription_period');
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
        Schema::dropIfExists('e_journals');
    }
}
