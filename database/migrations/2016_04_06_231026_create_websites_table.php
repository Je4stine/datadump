<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
            $table->string('home_url');
            $table->string('name');
            $table->string('layout');
            $table->string('telephone');
            $table->string('email');
            $table->string('host');
            $table->string('password');
            $table->string('port');
            $table->string('logo');
            $table->string('promo_image');
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
        Schema::drop('websites');
    }
}
