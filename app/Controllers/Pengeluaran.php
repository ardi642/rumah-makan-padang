<?php

namespace App\Controllers;

class Pengeluaran extends BaseController
{

    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pengeluaran';
        $data['kolomTabel'] = ['no', 'id pengeluaran', 'label pengeluaran', 'nominal', 'waktu', 'keterangan', 'aksi'];
        return view('/pengeluaran/index', $data);
    }

    public function tambah()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pengeluaran';
        return view('/pengeluaran/tambah', $data);
    }

    public function edit($idPengeluaran)
    {
        $pengeluaranModel = model(App\Models\PengeluaranModel::class);
        $dataPengeluaran = $pengeluaranModel->find($idPengeluaran);
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pengeluaran';
        $data['idPengeluaran'] = $idPengeluaran;
        $data['dataPengeluaran'] = $dataPengeluaran;
        return view('/pengeluaran/edit', $data);
    }
}
