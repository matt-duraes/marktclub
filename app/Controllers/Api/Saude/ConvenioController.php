<?php

namespace App\Controllers\Api\Saude;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Saude\Convenio\ListarModel;

final class ConvenioController extends Controller
{
    public function getListar(Request $request): Response
    {
        $Convenio = new ListarModel(
            enderecoEstado: $request->endereco_estado,
            enderecoCidade: $request->endereco_cidade
        );
        return mensagemSucesso($Convenio->retorno);
    }

    public function getEstado(): Response
    {
        return mensagemSucesso([]);
    }

    public function getCidade(Request $request): Response
    {
        return mensagemSucesso([]);
    }

    public function getHtml(Request $request): Response
    {
        return mensagemSucesso([]);
    }
}
