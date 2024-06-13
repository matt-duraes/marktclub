<?php

namespace App\Controllers\Api\Parceiro;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Parceiro\Externo\ExternoModel;
use System\Interface\ControllerDownloadInterface;
use App\Models\Api\Parceiro\Externo\DownloadModel;

final class ExternoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerDownloadInterface
{
    public function getListar(Request $request): Response
    {
        $Externo = new ExternoModel();
        $Externo->set(lista: $request->dado());

        return mensagemSucesso($Externo->listarDados());
    }

    public function postDownload(Request $request): Response
    {
        $Download = new DownloadModel($request);
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }

    public function getBuscar(string $id): Response
    {
        return $this->retornoPadrao();
    }

    public function postSalvar(Request $request): Response
    {
        return $this->retornoPadrao();
    }

    private function retornoPadrao()
    {
        return mensagemSucesso([]);
    }
}
