<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\SolicitacaoContato\Ordem;
use App\Classes\SolicitacaoContato\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\SolicitacaoContato\SolicitacaoContatoModel;
use App\Models\Api\SolicitacaoContato\SolicitacaoContatoEntity;

class SolicitacaoContatoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Contato = new SolicitacaoContatoEntity();
        $Contato->uuid($id);
        return $this->retornoSucesso($Contato);
    }

    /**
     * @param SolicitacaoContatoEntity $contatoEntity
     * @param int                      $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(SolicitacaoContatoEntity $contatoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($contatoEntity, lista: [
                'empresa', 'tipo', 'loca', 'nome', 'email', 'telefone', 'mensagem',
                'url', 'data_criacao', 'data_atualizacao', 'status'
            ]),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Contato = new SolicitacaoContatoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->nome,
            $request->empresa,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($Contato->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Contato = new SolicitacaoContatoEntity();
        $Contato->set(lista: $request->dado());
        $Contato->salvar();
        return $this->retornoSucesso($Contato, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Contato = new SolicitacaoContatoEntity();
        $Contato->uuid($id);
        $Contato->set(lista: $request->dado());
        $Contato->salvar();
        return new Response(status: 204);
    }
}
