<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
  protected $table = 'pengeluaran';
  protected $allowedFields = [
    'id_pengeluaran', 'keterangan', 'nominal',
    'waktu', 'id_label'
  ];
  protected $primaryKey = 'id_pengeluaran';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
