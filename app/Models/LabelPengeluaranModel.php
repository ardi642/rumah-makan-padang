<?php

namespace App\Models;

use CodeIgniter\Model;

class LabelPengeluaranModel extends Model
{
  protected $table = 'label_pengeluaran';
  protected $allowedFields = ['id_label', 'label'];
  protected $primaryKey = 'id_label';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
