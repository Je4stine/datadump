<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('amount');
            $table->integer('user_id');
            $table->string('bonus');
            $table->string('fine');
            $table->string('rating');
            $table->mediumText('comments');
            $table->integer('status');
            $table->dateTime('deadline');
            $table->integer('paid');
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
        Schema::drop('assigns');
    }
}
