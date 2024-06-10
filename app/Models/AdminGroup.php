<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class AdminGroup extends Model
{
    //
    protected $fillable = ['name','description','permissions'];

    public function users(){
        return $this->hasMany(User::class);
    }
}
