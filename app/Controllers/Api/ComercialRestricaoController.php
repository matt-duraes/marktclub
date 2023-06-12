<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\SelectGeralModel;

final class ComercialRestricaoController extends Controller
{
    public function getSelect(Request $request): Response
    {
        $Empresa = new SelectGeralModel(TABELA_COMERCIAL_RESTRICAO);
        $dado = $Empresa->pegarSelect(
            indice: 'uuid',
            valor: 'titulo',
            titulo: $request->titulo
        );

        return mensagemSucesso($dado);
    }
}
