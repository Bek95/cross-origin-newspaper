<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeViewController extends Controller
{
    public function home()
    {
        return view('welcome');
    }
}
