<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressiveMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressive_milestones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->double('amount');
            $table->longText('instructions');
            $table->dateTime('deadline');
            $table->tinyInteger('status');
            $table->tinyInteger('paid');
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
        Schema::drop('progressive_milestones');
    }
}
