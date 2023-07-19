<?php

namespace App\Controllers;

class LabelPengeluaran extends BaseController
{

    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pengeluaran';
        $data['kolomTabel'] = ['no', 'label', 'aksi'];
        return view('/LabelPengeluaran/index', $data);
    }

    public function tambah()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pengeluaran';
        return view('/LabelPengeluaran/tambah', $data);
    }

    public function edit($idPengeluaran)
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pengeluaran';
        $data['idPengeluaran'] = $idPengeluaran;
        return view('/LabelPengeluaran/edit', $data);
    }
}
