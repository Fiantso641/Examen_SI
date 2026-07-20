<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Rediriger vers votre contrôleur Auth
        return redirect()->to('/login');
    }
}
