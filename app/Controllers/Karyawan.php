<?php

namespace App\Controllers;

class Karyawan extends BaseController
{

    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'karyawan';
        $data['kolomTabel'] = ['no', 'nama', 'email', 'no_telepon', 'alamat', 'aksi'];
        return view('/karyawan/index', $data);
    }

    public function tambah()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'karyawan';
        return view('/karyawan/tambah', $data);
    }

    public function edit($idKaryawan)
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'karyawan';
        $data['idKaryawan'] = $idKaryawan;
        return view('/karyawan/edit', $data);
    }
}
