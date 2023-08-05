<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Karyawan extends BaseController
{
  protected $validation;
  protected $rulesKaryawan;

  public function __construct()
  {
    $this->validation = \Config\Services::validation();
    $this->rulesKaryawan = [
      'email' => "required|valid_email|is_unique[karyawan.email]",
      'password' => 'required|min_length[8]',
      'konfirmasi_password' => 'required|min_length[8]',
      'nama_karyawan' => 'required'
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
      'email', 'nama_karyawan',
      'level', 'no_telepon', 'alamat'
    ];
    $karyawanModel = model(App\Models\KaryawanModel::class);
    $filterOrLike = [];
    foreach ($koloms as $kolom) {
      $filterOrLike[$kolom] = $searchValue;
    }

    $result = $karyawanModel->orLike($filterOrLike)
      ->orderBy($orderByColumn, $orderDir)
      ->limit($length, $start)
      ->findAll();
    $recordsTotal = $karyawanModel->countAll();
    $recordsFiltered = count($result);
    $resData = [
      'draw' => $this->request->getVar('draw'),
      'data' => $result,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered
    ];
    return $this->response->setJSON($resData);
  }

  public function find($idKaryawan)
  {
    $karyawanModel = model(App\Models\KaryawanModel::class);
    $data = $karyawanModel->find($idKaryawan);
    $resData = [
      'data' => $data
    ];
    return $this->response->setJSON($resData);
  }

  public function create()
  {
    $data = $this->request->getJSON(true);
    $karyawanModel = model(App\Models\KaryawanModel::class);
    $this->validation->setRules($this->rulesKaryawan);
    $validasi = $this->validation->run($data);
    $errorValidasi = $this->validation->getErrors();

    if (
      (!isset($errorValidasi['password']) &
        !isset($errorValidasi['konfirmasi_password'])) &
      ($data['password'] != $data['konfirmasi_password'])
    ) {
      $errorValidasi['konfirmasi_password'] = 'tidak cocok dengan password';
    }

    if (count($errorValidasi) > 0) {
      $resData = [
        'error' => $errorValidasi
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => 12]);
    $query = $karyawanModel->insert($data, false);
    if (!$query) {
      $resData = [
        'error' => $karyawanModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }

    $resData = [
      'data' => $data
    ];
    return $this->response->setJSON($resData);
  }

  public function delete($idKaryawan)
  {
    $karyawanModel = model(App\Models\KaryawanModel::class);
    $query = $karyawanModel->delete($idKaryawan);
    if (!$query) {
      $resData = [
        'error' => $karyawanModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }

  public function update($idKaryawan)
  {
    $karyawanModel = model(App\Models\KaryawanModel::class);
    $data = $this->request->getJSON(true);
    // echo json_encode($data);
    // die();
    unset($this->rulesKaryawan['password']);
    $this->validation->setRules($this->rulesKaryawan);
    $this->validation->setRule('email', 'Email', "required|valid_email|is_unique[karyawan.email,id_karyawan,$idKaryawan]");
    $this->validation->setRule('password_lama', 'Password Lama', 'required|min_length[8]');
    $this->validation->setRule('password_baru', 'Password baru', 'required|min_length[8]');
    $this->validation->run($data);
    $errorValidasi = $this->validation->getErrors();
    $dataKaryawan = $karyawanModel->where('id_karyawan', $idKaryawan)->first();

    if (!isset($data['password_lama']) or $data['password_lama'] == "") {
      unset(
        $errorValidasi['password_lama'],
        $errorValidasi['password_baru'],
        $errorValidasi['konfirmasi_password']
      );
    } 
    else {
      if (!password_verify($data['password_lama'], $dataKaryawan['password']))
        $errorValidasi['password_lama'] = 'password tidak sesuai';

      if ($data['password_lama'] == $data['password_baru'])
        $errorValidasi['password_baru'] = 'password baru tidak boleh sama dengan password lama';

      if (
        !isset($errorValidasi['password_baru']) and !isset($errorValidasi['konfirmasi_password'])
        and $data['password_baru'] != $data['konfirmasi_password']
      ) {
        $errorValidasi['konfirmasi_password'] = 'tidak cocok dengan password baru';
      }
    }

    if (count($errorValidasi) > 0) {
      $resData = [
        'error' => $errorValidasi
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    if (isset($data['password_baru']))
      $data['password'] = password_hash($data['password_baru'], PASSWORD_DEFAULT, ['cost' => 12]);

    $query = $karyawanModel->update($idKaryawan, $data);
    if (!$query) {
      $resData = [
        'error' => $karyawanModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }
}
