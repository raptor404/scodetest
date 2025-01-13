<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;

class TestController extends Controller
{

    public function emailForm()
    {
        return view('dev.email-form');
    }
}
