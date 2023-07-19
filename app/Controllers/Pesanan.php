<?php

namespace App\Controllers;

class Pesanan extends BaseController
{

    public function index()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        $data['kolomTabel'] = [
            'no', 'id pesanan', 'banyak menu', 'banyak porsi', 'label pesanan',
            'uang pelanggan', 'total_bayar', 'uang_kembalian', 'waktu', 'aksi'
        ];
        return view('/pesanan/index', $data);
    }

    public function tambah()
    {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        return view('/pesanan/tambah', $data);
    }

    public function edit($idPesanan)
    {
        $pesananModel = model(App\Models\PesananModel::class);
        $dataPesanan = $pesananModel->find($idPesanan);
        $tanggalPesanan = date("Y-m-d", strtotime($dataPesanan['waktu']));
        $tanggalSekarang = date("Y-m-d");

        if ($tanggalPesanan != $tanggalSekarang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "tindakan ilegal karena tanggal pesanan tidak sesuai dengan tanggal sekarang"
            );
        }

        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        $data['idPesanan'] = $idPesanan;
        return view('/pesanan/edit', $data);
    }

    public function detail($idPesanan)
    {

        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = 'pesanan';
        $data['idPesanan'] = $idPesanan;
        return view('/pesanan/detail', $data);
    }
}
