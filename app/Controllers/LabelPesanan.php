<?php

namespace App\Controllers;

class LabelPesanan extends BaseController
{

    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        $data['kolomTabel'] = ['no', 'label', 'aksi'];
        return view('/LabelPesanan/index', $data);
    }

    public function tambah()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        return view('/LabelPesanan/tambah', $data);
    }

    public function edit($idPesanan)
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        $data['idPesanan'] = $idPesanan;
        return view('/LabelPesanan/edit', $data);
    }
}
