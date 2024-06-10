<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTopUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_top_ups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->double('amount');
            $table->double('usd_rate');
            $table->string('via');
            $table->string('reference');
            $table->integer('currency_id');
            $table->integer('redeemed_points');
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
        Schema::drop('account_top_ups');
    }
}
