<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypaltxns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('amount');
            $table->string('currency');
            $table->string('usd_rate');
            $table->string('txn_id');
            $table->string('state');
            $table->string('create_time');
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
        Schema::drop('paypaltxns');
    }
}
