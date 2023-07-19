<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
  protected $table = 'detail_pesanan';
  protected $allowedFields = ['id_detail_pesanan', 'id_pesanan', 'id_menu', 'harga_tertentu', 'jumlah'];
  protected $primaryKey = 'id_detail_pesanan';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
