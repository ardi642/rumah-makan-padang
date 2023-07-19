<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class LabelPesanan extends BaseController
{
  protected $validation;
  protected $labelPesananModel;

  public function __construct()
  {
    $this->labelPesananModel = model(App\Models\labelPesananModel::class);
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

    $labelPesananModel = $this->labelPesananModel;

    $result = $labelPesananModel->orLike([
      'label' => $searchValue
    ])
      ->orderBy($orderByColumn, $orderDir)
      ->limit($length, $start)
      ->findAll();
    $recordsTotal = $labelPesananModel->countAll();
    $recordsFiltered = count($result);

    $resData = [
      'draw' => $this->request->getVar('draw'),
      'data' => $result,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
    ];
    return $this->response->setJSON($resData);
  }

  public function find($idLabel)
  {
    $labelPesananModel = $this->labelPesananModel;
    $data = $labelPesananModel->find($idLabel);
    $resData = [
      'data' => $data
    ];
    return $this->response->setJSON($resData);
  }

  public function findByFilters()
  {
    $filters = $this->request->getVar();
    $labelPesananModel = $this->labelPesananModel;
    $result = $labelPesananModel
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
    $labelPesananModel = $this->labelPesananModel;
    $this->validation->setRules([
      'label' => "required|is_unique[label_pesanan.label, label, {$data['label']}]"
    ]);

    if (!$this->validation->run($data)) {
      $resData = [
        'error' => $this->validation->getErrors()
      ];

      return $this->response
        ->setStatusCode(400)
        ->setJSON($resData);
    }

    $query = $labelPesananModel->insert($data, false);
    if (!$query) {
      $resData = [
        'error' => $labelPesananModel->errors()
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
    $labelPesananModel = $this->labelPesananModel;
    $query = $labelPesananModel->delete($idLabel);
    if (!$query) {
      $resData = [
        'error' => $labelPesananModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }

  public function update($idLabel)
  {
    $labelPesananModel = $this->labelPesananModel;
    $data = $this->request->getJSON(true);
    $this->validation->setRules([
      'label' => "required|is_unique[label_pesanan.label, label, {$data['label']}]"
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

    $query = $labelPesananModel->update($idLabel, $data);
    if (!$query) {
      $resData = [
        'error' => $labelPesananModel->errors()
      ];
      return $this->response
        ->setStatusCode(500)
        ->setJSON($resData);
    }
    return $this->response->setJSON(true);
  }
}
