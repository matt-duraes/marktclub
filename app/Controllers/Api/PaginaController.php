<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\Pagina\CinemaModel;
use App\Models\Api\Pagina\TurismoModel;

final class PaginaController extends Controller
{
    public function getTurismo()
    {
        return mensagemSucesso((new TurismoModel())->pegarHtml());
    }

    public function getCinema()
    {
        return mensagemSucesso((new CinemaModel())->pegarHtml());
    }
}
