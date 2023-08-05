<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index() {
        $session = session();
        if ($session->get('login')) {
            return redirect()
                ->to(base_url());
        }
        return view('/auth/login');
    }
}