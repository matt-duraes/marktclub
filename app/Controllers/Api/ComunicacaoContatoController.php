<?php

namespace App\Controllers\Api;

use App\Classes\ComunicacaoContato\Ordem;
use App\Classes\ComunicacaoContato\Status;
use App\Models\Api\ComunicacaoContato\ComunicacaoContatoEntity;
use App\Models\Api\ComunicacaoContato\ComunicacaoContatoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class ComunicacaoContatoController extends Controller implements
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
        $Contato = new ComunicacaoContatoEntity();
        $Contato->uuid($id);
        return $this->retornoSucesso($Contato);
    }

    /**
     * @param ComunicacaoContatoEntity $contatoEntity
     * @param int                      $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ComunicacaoContatoEntity $contatoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $contatoEntity,
                lista: [
                    'nome', 'email', 'telefone', 'mensagem', 'parceiro',
                    'url', 'status', 'data_criacao', 'data_atualizacao'
                ]
            ),
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
        $Contato = new ComunicacaoContatoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Data($request->data_criacao_de),
            new Data($request->data_criacao_ate),
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
        $Contato = new ComunicacaoContatoEntity();
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
        $Contato = new ComunicacaoContatoEntity();
        $Contato->uuid($id);
        $Contato->set(lista: $request->dado());
        $Contato->salvar();
        return new Response(status: 204);
    }
}
