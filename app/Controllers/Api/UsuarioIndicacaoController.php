<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioIndicacao\Helper;
use App\Classes\UsuarioIndicacao\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\UsuarioIndicacao\IndicacaoModel;
use App\Models\Api\UsuarioIndicacao\IndicacaoEntity;

final class UsuarioIndicacaoController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{

    public function postSalvar(Request $request): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->set(lista: $request->dado());
        $Indicacao->salvar();

        return $this->retornoSucesso($Indicacao, 201);
    }

    public function getListar(Request $request): Response
    {
        $Indicacao = new IndicacaoModel($request);
        $dado = $Indicacao->listar();

        $dado->lista = criptografarDado(
            dado: $dado->lista,
            criptografia: Helper::CRIPTOGRAFAR,
            lista: true
        );

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);

        return $this->retornoSucesso($Indicacao);
    }

    private function retornoSucesso(IndicacaoEntity $Indicacao, int $status = 200)
    {
        $dado = pegarPropriedadeDaEntity($Indicacao, lista: [
            'id', 'nome', 'email', 'telefone', 'quem_indicou', 'usuario_ativo', 'data_criacao',
            'data_atualizacao', 'status',
        ]);
        return mensagemSucesso($dado, $status, Helper::CRIPTOGRAFAR);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);
        $Indicacao->status = new Status($request->status);
        $Indicacao->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);
        $Indicacao->destruir();

        return new Response(status: 204);
    }
}
