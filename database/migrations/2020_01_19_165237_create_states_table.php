<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('width');
            $table->integer('heigth');
            $table->integer('difficulty');
            $table->json('vector');
            $table->integer('empty');
            $table->integer('word_num');
            $table->json('cursor');
            $table->json('cursor_new');
            $table->integer('direction');
            $table->json('list');

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
        Schema::dropIfExists('states');
    }
}
