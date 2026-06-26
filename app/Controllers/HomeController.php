<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return redirect()->to('/dashboard');
    }

    public function dashboard()
    {
        return $this->render('home/dashboard');
    }

    public function estatisticas()
    {
        return $this->render('home/estatisticas');
    }
}