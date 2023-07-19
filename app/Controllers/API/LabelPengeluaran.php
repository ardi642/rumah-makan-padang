<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class LabelPengeluaran extends BaseController
{
  protected $validation;
  protected $labelPengeluaranModel;

  public function __construct()
  {
    $this->labelPengeluaranModel = model(App\Models\LabelPengeluaranModel::class);
    $this->validation = \Config\Services::validation();
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

    $labelPengeluaranModel = $this->labelPengeluaranModel;

    $result = $labelPengeluaranModel->orLike([
      'label' => $searchValue
    ])
      ->orderBy($orderByColumn, $orderDir)
      ->limit($length, $start)
      ->findAll();
    $recordsTotal = $labelPengeluaranModel->countAll();
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
    $labelPengeluaranModel = $this->labelPengeluaranModel;
    $labels = $labelPengeluaranModel->findAll();
    $resData = [
      'labels' => $labels
    ];
    return $this->response->setJSON($resData);
  }

  public function find($idLabel)
  {
    $labelPengeluaranModel = $this->labelPengeluaranModel;
    $data = $labelPengeluaranModel->find($idLabel);
    $resData = [
      'data' => $data
    ];
    return $this->response->setJSON($resData);
  }

  public function findByFilters()
  {
    $filters = $this->request->getVar();
    $labelPengeluaranModel = $this->labelPengeluaranModel;
    $result = $labelPengeluaranModel
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
    $labelPengeluaranModel = $this->labelPengeluaranModel;
    $this->validation->setRules([
      'label' => "required|is_unique[label_pengeluaran.label, label, {$data['label']}]"
    ]);

    if (!$this->validation->run($data)) {
      $resData = [
        'error' => $this->validation->getErrors()
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $query = $labelPengeluaranModel->insert($data, false);
    if (!$query) {
      $resData = [
        'error' => $labelPengeluaranModel->errors()
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

  public function delete($idLabel)
  {
    $labelPengeluaranModel = $this->labelPengeluaranModel;
    $query = $labelPengeluaranModel->delete($idLabel);
    if (!$query) {
      $resData = [
        'error' => $labelPengeluaranModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }

  public function update($idLabel)
  {
    $labelPengeluaranModel = $this->labelPengeluaranModel;
    $data = $this->request->getJSON(true);
    $this->validation->setRules([
      'label' => "required|is_unique[label_pengeluaran.label, label, {$data['label']}]"
    ]);
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

    $query = $labelPengeluaranModel->update($idLabel, $data);
    if (!$query) {
      $resData = [
        'error' => $labelPengeluaranModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }
}
