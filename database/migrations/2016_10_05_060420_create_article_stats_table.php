<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('post_website_id');
            $table->string('ip_address');
            $table->string('country');
            $table->string('referrer');
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
        Schema::drop('article_stats');
    }
}
