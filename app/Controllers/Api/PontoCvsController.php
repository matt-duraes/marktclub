<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\PontoCvs\PontoModel;
use App\Models\Api\PontoCvs\PontoEntity;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;

final class PontoCvsController extends Controller implements
    SalvarInterface,
    ListarInterface
{
    public function postSalvar(Request $request)
    {
        $Ponto = new PontoEntity();
        $Ponto->ponto_solicitado = $request->ponto_solicitado;
        $Ponto->salvar();

        return $this->retornoPadrao($Ponto, 201);
    }

    private function retornoPadrao(PontoEntity $Ponto, int $status = 200)
    {
        $dado = pegarPropriedadeDaEntity(
            $Ponto,
            lista: ['id', 'ponto_solicitado', 'data_solicitacao', 'voucher', 'data_voucher', 'status'],
        );
        return mensagemSucesso($dado, status: $status);
    }

    public function getListar(Request $request)
    {
        $Ponto = new PontoModel($request);
        $dado = $Ponto->listarDados();

        return mensagemSucesso($dado);
    }
}
