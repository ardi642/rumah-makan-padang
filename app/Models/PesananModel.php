<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
  protected $table = 'pesanan';
  protected $allowedFields = [
    'id_pesanan', 'uang_pelanggan', 'uang_kembalian',
    'total_bayar', 'waktu', 'waktu_update', 'id_label'
  ];
  protected $primaryKey = 'id_pesanan';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
