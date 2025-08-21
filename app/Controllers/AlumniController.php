<?php

namespace App\Controllers;

class AlumniController extends BaseController
{
    public function dashboard()
    {
        return view('alumni/dashboard');
    }
  
    public function questioner()
   {
        // arahkan ke folder alumni/questioner/index.php
        return view('alumni/questioner/index');
    }

    public function supervisi()
    {

        return view('alumni/supervisi');
    }

   
}
