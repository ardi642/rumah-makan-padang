<?php

namespace App\Controllers;

class Menu extends BaseController
{

    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'menu';
        $data['kolomTabel'] = ['no', 'nama menu', 'kategori', 'harga', 'aksi'];
        return view('/menu/index', $data);
    }

    public function tambah()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'menu';
        return view('/menu/tambah', $data);
    }

    public function edit($idMenu)
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'menu';
        $data['idMenu'] = $idMenu;
        return view('/menu/edit', $data);
    }
}
