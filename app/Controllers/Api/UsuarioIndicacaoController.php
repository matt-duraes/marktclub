<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioIndicacao\Helper;
use App\Classes\UsuarioIndicacao\Status;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Models\Api\UsuarioIndicacao\IndicacaoModel;
use App\Models\Api\UsuarioIndicacao\IndicacaoEntity;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UsuarioIndicacaoController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface,
    AtualizarInterface,
    DeletarInterface
{

    public function postSalvar(Request $request)
    {
        $dado = $request->dadoDecode(
            chavePrivada: TOKEN['app']->chave_privada,
            descriptografar: Helper::CRIPTOGRAFAR
        );

        $Indicacao = new IndicacaoEntity($request->usuario);
        $Indicacao->set(lista: $dado);
        $Indicacao->salvar();

        return $this->retornoSucesso($Indicacao, 201);
    }

    public function getListar(Request $request)
    {
        $Indicacao = new IndicacaoModel($request);
        $dado = $Indicacao->listar();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFAR);

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id)
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
            'id', 'nome', 'email', 'telefone', 'quem_indicou', 'usuario', 'data_criacao',
            'data_atualizacao', 'status',
        ]);
        return mensagemSucesso($dado, $status, Helper::CRIPTOGRAFAR);
    }

    public function putAtualizar(Request $request, string $id)
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);
        $Indicacao->status = new Status($request->status);
        $Indicacao->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);
        $Indicacao->destruir();

        return new Response(status: 204);
    }
}
