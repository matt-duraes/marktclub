<?php

namespace Painel\Demanda\Controllers;

use Controller\Controller;

final class DemandaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX/DETALHE
    |--------------------------------------------------------------------------
    */
    public function tarefa()
    {
        return view('painel.demanda.index');
    }

    public function novaTarefa()
    {
        return view('painel.demanda.nova');
    }
}
