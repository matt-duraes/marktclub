<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Helpers\ApiHelper;
use Http\Request;
use Http\Response;

final class AcessoRapidoController extends Controller
{
    public function index()
    {
        return view('acesso_rapido.index', [
            'menu' => 'acessorapido'
        ]);
    }


}
