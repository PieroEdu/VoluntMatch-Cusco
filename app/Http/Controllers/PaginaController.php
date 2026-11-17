<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaginaController extends Controller
{
    public function nosotros()
    {
        return view('nosotros');
    }

    public function proceso()
    {
        return view('proceso');
    }
}