<?php

namespace App\Controllers\Api;

use App\Classes\UsuarioIndicacao\Helper;
use App\Classes\UsuarioIndicacao\Ordem;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\UsuarioIndicacao\IndicacaoEntity;
use App\Models\Api\UsuarioIndicacao\IndicacaoModel;
use Controller\Controller;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class UsuarioIndicacaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->uuid($id, mensagem: 'Indicação não encontrada ou inexistente');
        return $this->retornoSucesso($Indicacao);
    }

    public function postSalvar(Request $request): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->set(lista: $request->dado());
        $Indicacao->salvar();
        return $this->retornoSucesso($Indicacao, 201);
    }

    public function postAtivar(Request $request): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->buscar([
            'OR',
            ['hash', $request->hash],
            ['email', $request->email]
        ], mensagem: 'Indicação não encontrada ou inexistente');

        if ($Indicacao->status->numero() == (new Status(Status::INDICADO))->numero()) {
            return $this->retornoSucesso($Indicacao);
        }

        return mensagemErro('Indicação já ativada', 'Essa indicação já foi ativada, por favor, tente novamente.');
    }

    private function retornoSucesso(IndicacaoEntity $Indicacao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Indicacao, lista: [
                'id', 'nome', 'email', 'telefone', 'quem_indicou', 'hash',
                'usuario_ativo', 'data_criacao', 'data_atualizacao', 'status'
            ]),
            $status,
            Helper::CRIPTOGRAFAR
        );
    }

    public function getListar(Request $request): Response
    {
        $Indicacao = new IndicacaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->pesquisa,
            $request->empresa,
            $request->nome,
            $request->email,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        $dado = $Indicacao->listarDados();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFAR, lista: true);
        return mensagemSucesso($dado);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->uuid($id, mensagem: 'Indicação não encontrada ou inexistente');
        $Indicacao->set(lista: $request->dado());
        $Indicacao->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->uuid($id, mensagem: 'Indicação não encontrada ou inexistente');
        $Indicacao->destruir();
        return new Response(status: 204);
    }
}
