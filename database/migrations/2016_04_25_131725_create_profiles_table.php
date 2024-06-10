<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('other_company');
            $table->string('style_ids');
            $table->string('subject_ids');
            $table->string('native_language');
            $table->string('academic_id');
            $table->longText('about');
            $table->integer('cv_file_id');
            $table->integer('step');
            $table->string('cert_title');
            $table->integer('cert_file_id');
            $table->longText('sample_essays');
            $table->string('payment_terms');
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
        Schema::drop('profiles');
    }
}
