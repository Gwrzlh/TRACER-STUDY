<?php

namespace App\Controllers;

class Homepage extends BaseController
{
    public function index()
    {
        return view('LandingPage/homepage');
    }
}
