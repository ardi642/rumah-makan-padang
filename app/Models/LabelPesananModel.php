<?php

namespace App\Models;

use CodeIgniter\Model;

class LabelPesananModel extends Model
{
  protected $table = 'label_pesanan';
  protected $allowedFields = ['id_label', 'label'];
  protected $primaryKey = 'id_label';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
