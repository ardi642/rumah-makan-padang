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
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];
    }

    public function handleLogin() {
        $data = $this->request->getJSON(true);
        $db      = \Config\Database::connect();
        $builder = $db->table('karyawan');
        $dataKaryawan = $builder
            ->where('email', $data['email'])
            ->get()
            ->getRowArray();

        $validasi = [];
        if ($dataKaryawan == null) {
            $validasi['email'] = 'email karyawan tidak terdaftar';
            if ($data['password'] == '') {
                $validasi['password'] = 'password tidak boleh kosong';
            }
        }
        else if (!password_verify($data['password'], $dataKaryawan['password'])) {
            $validasi['password'] = 'password yang dimasukkan tidak sesuai';
        }

        if (count($validasi) > 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                'validasi' => $validasi
            ]);
        }

        $dataKaryawan['login'] = true;
        unset($dataKaryawan['password']);
        $session = session();
        $session->set($dataKaryawan);

        return $this->response->setJSON(true);
    }
}