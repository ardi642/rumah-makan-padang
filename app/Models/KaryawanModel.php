<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
  protected $table = 'karyawan';
  protected $allowedFields = [
    'id_karyawan', 'username', 'password', 'email',
    'nama_karyawan', 'level', 'no_telepon', 'alamat'
  ];
  protected $primaryKey = 'id_karyawan';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
}
