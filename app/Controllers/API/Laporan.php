<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\Database\RawSql;
use DateTime;

class Laporan extends BaseController
{
  public function __construct()
  {

  }

  public function selectDatatable()
  {
    $params = $this->request->getVar();
    $idLabelPesanan = $params['id_label_pesanan'] ?? null;
    $idLabelPengeluaran = $params['id_label_pengeluaran'] ?? null;
    $tipeWaktu = $params['tipe_waktu'] ?? 'date';

    $searchValue = $params['search']['value'];
    $orderByIndex = $params['order'][0]['column'];
    $orderByColumn = $params['columns'][$orderByIndex]['data'];
    $orderDir = $params['order'][0]['dir'];
    $start = $params['start'];
    $length = $params['length'];
    $koloms = ['pemasukan', 'pengeluaran', 'waktu'];

    $filterOrLike = [];
    foreach ($koloms as $kolom) {
      $filterOrLike[$kolom] = $searchValue;
    }

    $db      = \Config\Database::connect();
    // $builder = $db->table('pesanan');

    // if ($tipeWaktu == 'date') {
    //   $sqlSelectWaktu = "DATE(pesanan.waktu) AS waktu";
    //   $sqlJoinWaktu = 'DATE(pengeluaran.waktu) = DATE(pesanan.waktu)';

    // }
    // else if ($tipeWaktu == 'month') {
    //   $sqlSelectWaktu = "CONCAT(YEAR(pesanan.waktu), '-', MONTH(pesanan.waktu)) AS waktu";
    //   $sqlJoinWaktu = 'MONTH(pengeluaran.waktu) = MONTH(pesanan.waktu) AND YEAR(pengeluaran.waktu) = YEAR(pesanan.waktu)';
    // }
    // $builder->select("SUM(pesanan.total_bayar) AS pemasukan, SUM(pengeluaran.nominal) AS pengeluaran, $sqlSelectWaktu");

    // $builder->join('pengeluaran', new RawSql($sqlJoinWaktu), 'INNER')
    // ->groupBy(['DATE(pesanan.waktu)', 'DATE(pengeluaran.waktu)'])
    // ->orHavingGroupStart()
    // ->orHavingLike($filterOrLike)
    // ->havingGroupEnd();



    // $sql = "SELECT SUM(pemasukan) AS pemasukan, SUM(pengeluaran) AS pengeluaran, 
    //         waktu_transaksi AS waktu FROM 
    //         (
    //           SELECT SUM(total_bayar) AS pemasukan, 0 AS pengeluaran,  
    //           DATE(waktu) AS waktu_transaksi FROM pesanan
    //           GROUP BY waktu_transaksi 
    //           UNION SELECT 
    //           0 AS pemasukan, SUM(nominal) AS pengeluaran,  DATE(waktu) AS waktu_transaksi 
    //           FROM pengeluaran GROUP BY waktu_transaksi
    //         ) 
    //         AS subq GROUP BY waktu $filterWaktu";

    $subquery = $db->table('pesanan')
      ->select('SUM(total_bayar) AS pemasukan, 0 as pengeluaran, DATE(waktu) AS waktu_transaksi')
      ->groupBy('waktu_transaksi');
    
    $union = $db->table('pengeluaran')
      ->select('0 as pemasukan, SUM(nominal) AS pengeluaran, DATE(waktu) AS waktu_transaksi')
      ->groupBy('waktu_transaksi');

    if (isset($params['id_label_pesanan'])) {
      $subquery->where('id_label', $params['id_label_pesanan']);
    }

    if (isset($params['id_label_pengeluaran'])) {
      $union->where('id_label', $params['id_label_pengeluaran']);
    }

    
    $subquery->union($union);
    $builder  = $db->newQuery()->fromSubquery($subquery, 'subq');
    $builder->select("SUM(pemasukan) AS pemasukan, SUM(pengeluaran) AS pengeluaran, 
      waktu_transaksi AS waktu, (SUM(pemasukan) - SUM(pengeluaran)) AS selisih");

    if ($tipeWaktu == 'month') {
      $builder->groupBy(["MONTH(waktu), YEAR(waktu)"]);
    }
    else if ($tipeWaktu == 'date') {
      $builder->groupBy("waktu");
    }

    $recordsTotal = $builder->countAllResults($reset = false);

    if ($tipeWaktu == 'month') {
      if (isset($params['tanggal_dari'])) {
        $params['tanggal_dari'] .= "-01";
      }

      if (isset($params['tanggal_sampai'])) {
        $params['tanggal_sampai'] .= "-31";
      }
    }

    if (isset($params['tanggal_dari']))
      $builder->having('DATE(waktu) >= ', $params['tanggal_dari']);
      
    if (isset($params['tanggal_sampai'])) {
      $builder->having('DATE(waktu) <= ', $params['tanggal_sampai']);
    }

    
    $builder->HavingGroupStart()
      ->orHavingLike($filterOrLike)
      ->havingGroupEnd()
      ->orderBy($orderByColumn, $orderDir);
    
    $result = $builder->get()->getResultArray();

    $recordsFiltered = count($result);
    $resData = [
      'data' => $result,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered
    ];
    return $this->response->setJSON($resData);
  }

}
