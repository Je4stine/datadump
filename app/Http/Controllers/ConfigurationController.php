<?php

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    //
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    public function index(){

    }
}
