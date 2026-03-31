<?php

namespace App\Http\Controllers;

class LocalController extends Controller
{
    public function index()
    {
        return view('locales.index');
    }
}
