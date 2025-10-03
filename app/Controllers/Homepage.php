<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Homepage extends BaseController
{
    public function index()
    {
        return view('LandingPage/homepage');
    }
}
