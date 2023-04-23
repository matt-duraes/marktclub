<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class OdontologicoController extends Controller
{
    public function index()
    {
        return view('odontologico.index', [
            'menu' => 'odontologico'
        ]);
    }
}
