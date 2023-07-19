<?php

namespace App\Controllers;

class Hitung extends BaseController
{

  public function index()
  {
    $db      = \Config\Database::connect();
    $builder = $db->table('detail_pesanan');
    $result = $builder->select('sum(harga_tertentu * jumlah) AS pemasukkan, sum(jumlah) as total_makanan_terjual,
                          avg(harga_tertentu * jumlah) as rata_rata')
      ->join('pesanan', 'pesanan.id_pesanan = detail_pesanan.id_pesanan')
      ->where('DATE(waktu)', '2023-07-01')
      ->get()
      ->getResultArray();
    $data['rows'] = $result;
    d($data['rows']);
    return view('/Hitung/index', $data);
    // pemasukkan, total makanan terjual, rata - rata
  }
}
