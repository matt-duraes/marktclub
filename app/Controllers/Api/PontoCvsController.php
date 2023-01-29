<?php

namespace App\Controllers\Api;

use App\Classes\PontoCvs\Helper;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\PontoCvs\Status;
use App\Models\Api\PontoCvs\PontoModel;
use App\Models\Api\PontoCvs\PontoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use Modules\Email;

final class PontoCvsController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function postSalvar(Request $request)
    {
        $Ponto = new PontoEntity($request->cpf);

        $Ponto->ponto_solicitado = $request->ponto_solicitado;
        $Ponto->nome = $request->nome;
        $Ponto->email = new Email($request->email);

        $Ponto->salvar();

        return $this->retornoSucesso($Ponto, 201);
    }

    public function getListar(Request $request)
    {
        $Ponto = new PontoModel($request);

        $dado = $Ponto->listarDados();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFAR);

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id)
    {
        validarUuid($id);

        $Ponto = new pontoEntity;
        $Ponto->id($id);

        return $this->retornoSucesso($Ponto);
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

    private function retornoSucesso(PontoEntity $Ponto, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Ponto,
                lista: [
                    'id', 'usuario', 'ponto_solicitado', 'voucher', 'mensagem', 'data_solicitacao', 'data_voucher',
                    'data_atualizacao', 'pedido_codigo', 'status'
                ]
            ),
            status: $status,
            criptografar: Helper::CRIPTOGRAFAR
        );
    }
}
