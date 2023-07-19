<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Menu extends BaseController
{
  protected $validation;
  protected $rulesMenu;

  public function __construct()
  {
    $this->validation = \Config\Services::validation();
    $this->rulesMenu = [
      'kategori' => 'required',
      'nama_menu' => 'required|nama_menu_duplikat',
      'harga' => 'required'
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

    $menuModel = model(App\Models\MenuModel::class);
    $result = $menuModel->orLike([
      'kategori' => $searchValue,
      'nama_menu' => $searchValue,
      'harga' => $searchValue
    ])
      ->orderBy($orderByColumn, $orderDir)
      ->limit($length, $start)
      ->findAll();
    $recordsTotal = $menuModel->countAll();
    $recordsFiltered = count($result);

    $resData = [
      'draw' => $this->request->getVar('draw'),
      'data' => $result,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
    ];
    return $this->response->setJSON($resData);
  }

  public function find($idMenu)
  {
    $menuModel = model(App\Models\MenuModel::class);
    $data = $menuModel->find($idMenu);
    $resData = [
      'data' => $data
    ];
    return $this->response->setJSON($resData);
  }

  public function findByFilters()
  {
    $filters = $this->request->getVar();
    $menuModel = model(App\Models\MenuModel::class);
    $result = $menuModel
      ->builder()
      ->like($filters)
      ->get()
      ->getResultArray();
    $resData = [
      'data' => $result
    ];
    return $this->response->setJSON($resData);
  }

  public function create()
  {
    $data = $this->request->getJSON(true);
    $menuModel = model(App\Models\MenuModel::class);
    $this->validation->setRules($this->rulesMenu);

    if (!$this->validation->run($data)) {
      $resData = [
        'error' => $this->validation->getErrors()
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $query = $menuModel->insert($data, false);
    if (!$query) {
      $resData = [
        'error' => $menuModel->errors()
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

  public function delete($idMenu)
  {
    $menuModel = model(App\Models\MenuModel::class);
    $query = $menuModel->delete($idMenu);
    if (!$query) {
      $resData = [
        'error' => $menuModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }

  public function update($idMenu)
  {
    $menuModel = model(App\Models\MenuModel::class);
    $data = $this->request->getJSON(true);
    $this->validation->setRules($this->rulesMenu);
    $this->validation->run($data);
    $errorValidasi = $this->validation->getErrors();

    $dataMenu = $menuModel->where('LOWER(TRIM(nama_menu))', strtolower($data['nama_menu']))->first();

    if ($dataMenu != null and $dataMenu['id_menu'] == $idMenu) {
      unset($errorValidasi['nama_menu']);
    }

    if (count($errorValidasi) > 0) {
      $resData = [
        'error' => $errorValidasi
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $query = $menuModel->update($idMenu, $data);
    if (!$query) {
      $resData = [
        'error' => $menuModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }
}
