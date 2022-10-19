<?php

namespace App\Controllers\Painel;

use Controller\Controller;

final class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ate = date('Y-m-d');
        $this->de = dataRemover($this->ate, 7, 'days');
    }

    public function index()
    {
        return view(arquivo: 'dashboard', var: [
            'appTitulo' => 'Dashboard',
            'app' => 'dashboard'
        ]);
    }
}
