<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'home';
        return view('admin_home', $data);
    }

    public function coba()
    {
        echo "Hello World";
    }
}
