<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use DateTime;

class Pesanan extends BaseController
{
  protected $validation;
  protected $rulesDetailPesanan;

  public function __construct()
  {
    $this->validation = \Config\Services::validation();
    $this->rulesDetailPesanan = [
      'id_menu' => 'required|numeric',
      'jumlah' => 'required|numeric',
    ];
  }

  public function selectDatatable()
  {
    $params = $this->request->getVar();
    $searchValue = $params['search']['value'];
    $orderByIndex = $params['order'][0]['column'];
    $orderByColumn = $params['columns'][$orderByIndex]['data'];
    $orderDir = $params['order'][0]['dir'];
    $start = $params['start'];
    $length = $params['length'];
    $koloms = [
      'uang_pelanggan', 'total_bayar', 'label', 'uang_kembalian',
      'banyak_menu', 'banyak_porsi', 'waktu'
    ];
    $pesananModel = model(App\Models\PesananModel::class);
    $filterOrLike = [];
    foreach ($koloms as $kolom) {
      $filterOrLike[$kolom] = $searchValue;
    }

    $builder = $pesananModel->builder()
      ->select('pesanan.id_pesanan, count(id_menu) as banyak_menu, sum(jumlah) as banyak_porsi, 
        label, uang_pelanggan, uang_kembalian, total_bayar, waktu')
      ->join('detail_pesanan', 'detail_pesanan.id_pesanan = pesanan.id_pesanan')
      ->join('label_pesanan', 'label_pesanan.id_label = pesanan.id_label')
      ->groupBy(['pesanan.id_pesanan'])
      ->orHavingGroupStart()
      ->orHavingLike($filterOrLike)
      ->havingGroupEnd();

    if (isset($params['tanggal_dari'])) {
      $builder->having('DATE(waktu) >= ', $params['tanggal_dari']);
    }

    if (isset($params['tanggal_sampai'])) {
      $builder->having('DATE(waktu) <= ', $params['tanggal_sampai']);
    }

    $result = $builder->orderBy($orderByColumn, $orderDir)
      ->limit($length, $start)
      ->get()
      ->getResultArray();

    $recordsTotal = $pesananModel->countAll();
    $recordsFiltered = count($result);
    $resData = [
      'draw' => $this->request->getVar('draw'),
      'data' => $result,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered
    ];
    return $this->response->setJSON($resData);
  }

  public function dataPesanan($idPesanan)
  {
    $pesananModel = model(App\Models\PesananModel::class);
    $detailPesananModel = model(App\Models\DetailPesananModel::class);
    $pesananBuilder = $pesananModel->builder();
    $detailPesananBuilder = $detailPesananModel->builder();

    $pesanans = $detailPesananBuilder
      ->select('detail_pesanan.id_menu, menu.nama_menu, harga_tertentu, jumlah')
      ->join('pesanan', 'pesanan.id_pesanan = detail_pesanan.id_pesanan')
      ->join('menu', 'menu.id_menu = detail_pesanan.id_menu')
      ->where('detail_pesanan.id_pesanan', $idPesanan)
      ->get()
      ->getResultArray();

    $pesanan = $pesananModel->builder()
      ->select('pesanan.*, label_pesanan.label')
      ->join('label_pesanan', 'label_pesanan.id_label = pesanan.id_label')
      ->where('id_pesanan', $idPesanan)
      ->get()
      ->getRowArray();

    $pesananMasuk = [
      ...$pesanan,
      'pesanans' => $pesanans
    ];
    $resData = [
      'pesananMasuk' => $pesananMasuk
    ];
    return $this->response->setJSON($resData);
  }

  public function create()
  {
    $data = $this->request->getJSON(true);
    $pesanans = $data['pesanans'];
    $pembayaran = $data['pembayaran'];
    $validasiPesanans = [];
    $validasiPembayaran = [];
    $validasiLabel = [];

    $panjangPesanans = count($pesanans);
    for ($i = 0; $i < $panjangPesanans; $i++) {
      $pesanan = $pesanans[$i];
      $validasiPesanan = [];
      if ($pesanan['id_menu'] == NULL)
        $validasiPesanan['id_menu'] = 'menu belum dipilih';
      if ($pesanan['jumlah'] < 1 or $pesanan['jumlah'] == NULL)
        $validasiPesanan['jumlah'] = 'jumlah pesanan menu minimal 1';

      if (count($validasiPesanan) > 0)
        $validasiPesanans["$i"] = $validasiPesanan;
    }

    if ($pembayaran['uang_pelanggan'] == NULL) {
      $validasiPembayaran['uang_pelanggan'] = 'uang pelanggan belum dimasukkan';
    } else if (($pembayaran['uang_pelanggan'] - $pembayaran['total_bayar']) < 0) {
      $validasiPembayaran['uang_pelanggan'] = 'uang pelanggan tidak cukup untuk melakukan pembayaran';
    }

    if ($data['id_label'] == NULL) {
      $validasiLabel['id_label'] = 'label pesanan belum dipilih';
    }

    if ((count($validasiPesanans) + count($validasiPembayaran) +
      count($validasiLabel)) > 0) {
      $resData = [
        'validasiPesanans' => $validasiPesanans,
        'validasiPembayaran' => $validasiPembayaran,
        'validasiLabel' => $validasiLabel
      ];
      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $pesananModel = model(App\Models\PesananModel::class);
    // $pesananBuilder = $pesananModel->builder();
    $detailPesananModel = model(App\Models\DetailPesananModel::class);
    $detailPesananBuilder = $detailPesananModel->builder();

    $db = db_connect();
    $db->transStart();

    $waktu = date('Y-m-d H:i:s');
    $queryInsertPesanan = $pesananModel->insert([
      'uang_pelanggan' => $pembayaran['uang_pelanggan'],
      'waktu' => $waktu,
      'id_label' => $data['id_label']
    ]);
    $id_pesanan = $pesananModel->getInsertID();

    $panjangPesanans = count($pesanans);
    $values = '';
    for ($i = 0; $i < $panjangPesanans; $i++) {
      $pesanans[$i]['id_pesanan'] = $id_pesanan;
      $pesanans[$i]['id_menu'] = $db->escape($pesanans[$i]['id_menu']);
      $pesanans[$i]['jumlah'] = $db->escape($pesanans[$i]['jumlah']);
      $pesanans[$i]['harga_tertentu'] = " (SELECT harga FROM menu WHERE id_menu = {$pesanans[$i]['id_menu']})";
      unset($pesanans[$i]['nama_menu']);

      if ($i < $panjangPesanans - 1)
        $values .= '(' . implode(", ", $pesanans[$i]) . '), ';
      else
        $values .= '(' . implode(", ", $pesanans[$i]) . ')';
    }
    $sql = "INSERT INTO `detail_pesanan` (`id_menu`, `harga_tertentu`, `jumlah`, `id_pesanan`) VALUES "
      . $values;

    $queryInsertDetailPesanan = $db->query($sql);

    $totalBayar = $detailPesananBuilder
      ->select('sum(harga_tertentu * jumlah) AS total_bayar')
      ->where(['id_pesanan' => $id_pesanan])
      ->get()
      ->getRow()
      ->total_bayar;

    $uangPelanggan = $pembayaran['uang_pelanggan'];
    $uangKembalian = $uangPelanggan - $totalBayar;
    if ($uangKembalian < 0) {
      $db->transRollback();
      $validasiPembayaran = 'uang pelanggan tidak cukup untuk melakukan pembayaran';
      $resData = [
        'validasiPesanans' => $validasiPesanans,
        'validasiPembayaran' => $validasiPembayaran,
        'validasiLabel' => $validasiLabel
      ];
      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $pesananModel->update($id_pesanan, [
      'uang_kembalian' => $uangKembalian,
      'total_bayar' => $totalBayar,
    ]);

    $db->transComplete();

    if ($db->transStatus() === false) {
      $resData = [
        'error' => 'database gagal melakukan transaksi pesanan'
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }

    $resData = [
      'id_pesanan' => $id_pesanan,
      'waktu' => $waktu,
      'id_label' => $data['id_label']
    ];

    return $this->response->setJSON($resData);
  }

  public function delete($idPesanan)
  {
    $pesananModel = model(App\Models\PesananModel::class);
    $query = $pesananModel->delete($idPesanan);
    if (!$query) {
      $resData = [
        'error' => $pesananModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }

  public function update($id_pesanan)
  {
    $data = $this->request->getJSON(true);
    $pesanans = $data['pesanans'];
    $pembayaran = $data['pembayaran'];
    $validasiPesanans = [];
    $validasiPembayaran = [];
    $validasiLabel = [];

    $panjangPesanans = count($pesanans);
    for ($i = 0; $i < $panjangPesanans; $i++) {
      $pesanan = $pesanans[$i];
      $validasiPesanan = [];
      if ($pesanan['id_menu'] == NULL)
        $validasiPesanan['id_menu'] = 'menu belum dipilih';
      if ($pesanan['jumlah'] < 1 or $pesanan['jumlah'] == NULL)
        $validasiPesanan['jumlah'] = 'jumlah pesanan menu minimal 1';

      if (count($validasiPesanan) > 0)
        $validasiPesanans["$i"] = $validasiPesanan;
    }

    if ($pembayaran['uang_pelanggan'] == NULL) {
      $validasiPembayaran['uang_pelanggan'] = 'uang pelanggan belum dimasukkan';
    } else if (($pembayaran['uang_pelanggan'] - $pembayaran['total_bayar']) < 0) {
      $validasiPembayaran['uang_pelanggan'] = 'uang pelanggan tidak cukup untuk melakukan pembayaran';
    }

    if ($data['id_label'] == NULL) {
      $validasiLabel['id_label'] = 'label pesanan belum dipilih';
    }

    if ((count($validasiPesanans) + count($validasiPembayaran) +
      count($validasiLabel)) > 0) {
      $resData = [
        'validasiPesanans' => $validasiPesanans,
        'validasiPembayaran' => $validasiPembayaran,
        'validasiLabel' => $validasiLabel
      ];
      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }


    $pesananModel = model(App\Models\PesananModel::class);
    // $pesananBuilder = $pesananModel->builder();
    $detailPesananModel = model(App\Models\DetailPesananModel::class);
    $detailPesananBuilder = $detailPesananModel->builder();

    $db = db_connect();
    $db->transStart();

    $panjangPesanans = count($pesanans);
    $values = '';
    for ($i = 0; $i < $panjangPesanans; $i++) {
      $pesanans[$i]['id_pesanan'] = $id_pesanan;
      $pesanans[$i]['id_menu'] = $db->escape($pesanans[$i]['id_menu']);
      $pesanans[$i]['jumlah'] = $db->escape($pesanans[$i]['jumlah']);
      $pesanans[$i]['harga_tertentu'] = " (SELECT harga FROM menu WHERE id_menu = {$pesanans[$i]['id_menu']})";
      unset($pesanans[$i]['nama_menu']);

      if ($i < $panjangPesanans - 1)
        $values .= '(' . implode(", ", $pesanans[$i]) . '), ';
      else
        $values .= '(' . implode(", ", $pesanans[$i]) . ')';
    }
    $sql = "INSERT INTO `detail_pesanan` (`id_menu`, `harga_tertentu`, `jumlah`, `id_pesanan`) VALUES "
      . $values;

    $queryDeleteDetailPesanan = $detailPesananModel->where('id_pesanan', $id_pesanan)->delete();

    $queryInsertDetailPesanan = $db->query($sql);

    $totalBayar = $detailPesananBuilder
      ->select('sum(harga_tertentu * jumlah) AS total_bayar')
      ->where(['id_pesanan' => $id_pesanan])
      ->get()
      ->getRow()
      ->total_bayar;

    $uangPelanggan = $pembayaran['uang_pelanggan'];
    $uangKembalian = $uangPelanggan - $totalBayar;

    if ($uangKembalian < 0) {
      $db->transRollback();
      $validasiPembayaran = 'uang pelanggan tidak cukup untuk melakukan pembayaran';
      $resData = [
        'validasiPesanans' => $validasiPesanans,
        'validasiPembayaran' => $validasiPembayaran,
        'validasiLabel' => $validasiLabel
      ];
      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $waktu = date('Y-m-d H:i:s');
    $pesananModel->update($id_pesanan, [
      'uang_kembalian' => $uangKembalian,
      'total_bayar' => $totalBayar,
      'uang_pelanggan' => $uangPelanggan,
      'waktu_update' => $waktu,
      'id_label' => $data['id_label']
    ]);

    $db->transComplete();

    if ($db->transStatus() === false) {
      $resData = [
        'error' => 'database gagal melakukan transaksi pesanan'
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }

    $resData = [
      'id_pesanan' => $id_pesanan,
      'waktu_update' => $waktu,
      'id_label' => $data['id_label']
    ];

    return $this->response->setJSON($resData);
  }
}
