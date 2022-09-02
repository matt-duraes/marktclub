<?php

namespace App\Controllers\Api;

use App\Classes\PontoCvs\Status;
use Http\Request;
use Controller\Controller;
use App\Models\Api\PontoCvs\PontoModel;
use App\Models\Api\PontoCvs\PontoEntity;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use Http\Response;

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

    public function getBuscar(string $id)
    {
        validarUuid($id);

        $Ponto = new pontoEntity;
        $Ponto->id($id);

        return $this->retornoPadrao($Ponto);
    }

    public function putAtualizar(Request $request, string $id)
    {
        validarUuid($id);

        $Ponto = new pontoEntity;
        $Ponto->id($id);
        $Ponto->mensagem = $request->mensagem ?? '';
        $Ponto->status = new Status($request->status);
        if($Ponto->status->numero() == 3 && !empty( $request->voucher)){
            $Ponto->voucher = $request->voucher;
        }
        $Ponto->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        validarUuid($id);

        $Ponto = new pontoEntity;
        $Ponto->id($id);
        $Ponto->destruir();

        return new Response(status: 204);
    }
}
