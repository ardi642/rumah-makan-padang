<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
  protected $table = 'menu';
  protected $allowedFields = ['id_menu', 'kategori', 'nama_menu', 'harga'];
  protected $primaryKey = 'id_menu';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
