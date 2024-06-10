<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterTest extends Model
{
    //
    protected $fillable = ['random_test_id'];
    public function randomTest(){
        return $this->belongsTo(RandomTest::class);
    }
}
