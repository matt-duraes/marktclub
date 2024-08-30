<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\Pagina\CinemaModel;
use App\Models\Api\Pagina\SamsungModel;
use App\Models\Api\Pagina\TurismoModel;

final class PaginaController extends Controller
{
    public function getListar(Request $request)
    {
        return mensagemSucesso([]);
    }

    public function getTurismo()
    {
        return mensagemSucesso((new TurismoModel())->pegarHtml());
    }

    public function getCinema()
    {
        return mensagemSucesso((new CinemaModel())->pegarHtml());
    }

    public function getSamsung(Request $request)
    {
        return mensagemSucesso((new SamsungModel($request))->pegarHtml());
    }
}
