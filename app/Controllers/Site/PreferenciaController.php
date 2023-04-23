<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Helpers\ApiHelper;
use Helpers\SocialHelper;
use Http\Request;
use Http\Response;

final class PreferenciaController extends Controller
{
    public function index()
    {

        return view('preferencia.index', [
            'tituloPagina' => 'Preferências',
            'parceiro' => [1,2,3],
            'url' => 'preferencia'
        ]);
    }

    public function boasVindas()
    {

        return view('preferencia.boasVindas', [
            'tituloPagina' => 'Preferências',
            'parceiro' => [1,2,3]
        ]);
    }

}
