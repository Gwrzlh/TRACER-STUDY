<?php

namespace App\Controllers;

class LandingPage extends BaseController
{
    public function home()
    {
        return view('LandingPage/Homepage');
    }
}
