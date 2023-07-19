<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Pengeluaran extends BaseController
{
  protected $validation;
  protected $pengeluaranModel;
  protected $pengeluaranRules;

  public function __construct()
  {
    $this->pengeluaranModel = model(App\Models\PengeluaranModel::class);
    $this->validation = \Config\Services::validation();
    $this->pengeluaranRules = [
      'keterangan' => 'required',
      'nominal' => 'required|numeric',
      'id_label' => 'required'
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
      'label', 'nominal', 'waktu', 'keterangan'
    ];
    $filterOrLike = [];
    foreach ($koloms as $kolom) {
      $filterOrLike[$kolom] = $searchValue;
    }
    $pengeluaranModel = $this->pengeluaranModel;
    // $data['kolomTabel'] = ['no', 'id pengeluaran', 'label pengeluaran', 'nominal', 'waktu', 'keterangan', 'aksi'];
    $result = $pengeluaranModel->builder()
      ->select('id_pengeluaran, label, nominal, waktu, keterangan')
      ->join('label_pengeluaran', 'label_pengeluaran.id_label = pengeluaran.id_label')
      ->orLike($filterOrLike)
      ->orderBy($orderByColumn, $orderDir)
      ->limit($length, $start)
      ->get()
      ->getResultArray();
    $recordsTotal = $pengeluaranModel->countAll();
    $recordsFiltered = count($result);

    $resData = [
      'draw' => $this->request->getVar('draw'),
      'data' => $result,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
    ];
    return $this->response->setJSON($resData);
  }

  public function selectAll()
  {
    $pengeluaranModel = $this->pengeluaranModel;
    $labels = $pengeluaranModel->findAll();
    $resData = [
      'labels' => $labels
    ];
    return $this->response->setJSON($resData);
  }

  public function find($idPengeluaran)
  {
    $pengeluaranModel = $this->pengeluaranModel;
    $data = $pengeluaranModel->find($idPengeluaran);
    $resData = [
      'data' => $data
    ];
    return $this->response->setJSON($resData);
  }

  public function findByFilters()
  {
    $filters = $this->request->getVar();
    $pengeluaranModel = $this->pengeluaranModel;
    $result = $pengeluaranModel
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
    if ($data['id_label'] == 'null')
      $data['id_label'] = null;
    $pengeluaranModel = $this->pengeluaranModel;
    $this->validation->setRules($this->pengeluaranRules);

    if (!$this->validation->run($data)) {
      $resData = [
        'error' => $this->validation->getErrors()
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $data['waktu'] = date("Y-m-d H:i:s");
    $query = $pengeluaranModel->insert($data, false);
    if (!$query) {
      $resData = [
        'error' => $pengeluaranModel->errors()
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

  public function delete($idPengeluaran)
  {
    $pengeluaranModel = $this->pengeluaranModel;
    $query = $pengeluaranModel->delete($idPengeluaran);
    if (!$query) {
      $resData = [
        'error' => $pengeluaranModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }

  public function update($idPengeluaran)
  {
    $pengeluaranModel = $this->pengeluaranModel;
    $data = $this->request->getJSON(true);
    $this->validation->setRules($this->pengeluaranRules);
    $this->validation->run($data);
    $errorValidasi = $this->validation->getErrors();

    if (count($errorValidasi) > 0) {
      $resData = [
        'error' => $errorValidasi
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $query = $pengeluaranModel->update($idPengeluaran, $data);
    if (!$query) {
      $resData = [
        'error' => $pengeluaranModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }
}
