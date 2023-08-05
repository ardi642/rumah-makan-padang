<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    
    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'laporan';
        return view('laporan/index', $data);
    }

    public function coba()
    {
        
        echo "Hello World";
    }
}
