<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_queries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keywords');
            $table->string('buying_type');
            $table->string('username');
            $table->json('condition');
            $table->integer('categoryId')->nullable();
            $table->boolean('checked')->default(false);
            $table->dateTime('last_check')->nullable();
            $table->string('productId')->nullable();
            $table->integer('query_data_id')->nullable();
            $table->string('thumbnail')->default('http://krivoy.co.uk/img/processing.jpg');
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
        Schema::dropIfExists('user_queries');
    }
}
