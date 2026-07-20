<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function index()
    {
        session_start();
        session_destroy();
        return redirect()->to('/');
    }
}
