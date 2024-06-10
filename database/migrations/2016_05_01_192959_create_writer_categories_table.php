<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWriterCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cpp');
            $table->string('amount');
            $table->string('inc_type');
            $table->string('name');
            $table->string('deadline');
            $table->string('allowed');
            $table->integer('deleted');
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
        Schema::drop('writer_categories');
    }
}
