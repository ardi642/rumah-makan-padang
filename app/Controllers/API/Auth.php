<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    protected $validation;
    protected $rulesLogin;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->rulesLogin = [
            'email' => 'required',
            'password' => 'required|min_length[8]',
        ];
    }

    public function handleLogin() {
        $data = $this->request->getJSON(true);
        $db      = \Config\Database::connect();
        $builder = $db->table('karyawan');
        $result = $builder
            ->where('email', $data['email'])
            ->where('password', $data['password'])
            ->get()
            ->getRowArray();

        $res = [
            'data' => $result,
        ];

        return $this->response->setJSON($res);
    }
}