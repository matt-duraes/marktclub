<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\PontoCvs\Status;
use App\Models\Api\PontoCvs\PontoModel;
use App\Models\Api\PontoCvs\PontoEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\AtualizarInterface;

final class PontoCvsController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface,
    AtualizarInterface
{
    public function postSalvar(Request $request)
    {
        $Ponto = new PontoEntity($request->cpf);
        $Ponto->ponto_solicitado = $request->ponto_solicitado;
        $Ponto->salvar();

        return mensagemSucesso($Ponto->retorno(), 201);
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

        return mensagemSucesso($Ponto->retorno());
    }

    public function putAtualizar(Request $request, string $id)
    {
        validarUuid($id);

        $Ponto = new pontoEntity;
        $Ponto->id($id);
        $Ponto->voucher = $request->voucher ?? '';
        $Ponto->mensagem = $request->mensagem ?? '';
        $Ponto->status = new Status($request->status);
        $Ponto->salvar();

        return new Response(status: 204);
    }
}
