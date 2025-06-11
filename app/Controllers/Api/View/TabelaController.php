<?php

namespace App\Controllers\Api\View;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\View\Tabela\BuscarModel;

final class TabelaController extends Controller
{
    public function getBuscar(string $tabela): Response
    {
        $Busca = new BuscarModel(
            tabela: $tabela,
        );
        return mensagemSucesso($Busca->retorno);
    }
}
