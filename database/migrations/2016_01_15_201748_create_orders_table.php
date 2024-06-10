<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('topic',300);
            $table->integer('subject_id');
            $table->integer('document_id');
            $table->string('spacing',20);
            $table->integer('academic_id');
            $table->string('urgency');
            $table->integer('pages');
            $table->integer('sources');
            $table->integer('style_id');
            $table->integer('status');
            $table->integer('language_id');
            $table->decimal('amount', 5, 2);
            $table->decimal('cpp', 5, 2);
            $table->longText('instructions');
            $table->dateTime('deadline');
            $table->integer('paid');
            $table->integer('discounted');
            $table->integer('website_id');
            $table->integer('writer_category_id');
            $table->integer('currency_id');
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
        Schema::drop('orders');
    }
}
