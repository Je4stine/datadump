<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TraitsController extends Controller
{
    //

    public function showTraits(User  $user){
        $traits = $user->traits()->orderBy('id','desc')->paginate(10);
        return view('traits.for_user',[

        ]);
    }
}
