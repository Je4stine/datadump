<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('assign_id');
            $table->integer('user_id');
            $table->string('file_for');
            $table->string('filename');
            $table->string('filesize');
            $table->string('file_type');
            $table->string('path');
            $table->integer('allow_client');
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
        Schema::drop('files');
    }
}
