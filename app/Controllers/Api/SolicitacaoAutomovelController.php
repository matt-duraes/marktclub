<?php

namespace App\Controllers\Api;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoAutomovel\Ordem;
use App\Models\Api\SolicitacaoAutomovel\AutomovelEntity;
use App\Models\Api\SolicitacaoAutomovel\AutomovelModel;
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

class SolicitacaoAutomovelController extends Controller implements
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
        $Automovel = new AutomovelEntity();
        $Automovel->uuid($id);
        return $this->retornoSucesso($Automovel);
    }

    /**
     * @param AutomovelEntity $Automovel
     * @param int             $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(AutomovelEntity $Automovel, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Automovel, lista: [
                'usuario', 'endereco_estado', 'endereco_cidade',
                'montadora', 'modelo', 'versao', 'cor', 'data_criacao',
                'data_atualizacao', 'mensagem', 'status'
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
        $Automovel = new AutomovelModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($Automovel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Automovel = new AutomovelEntity();
        $Automovel->set(lista: $request->dado());
        $Automovel->salvar();
        return $this->retornoSucesso($Automovel, 201);
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
        $Automovel = new AutomovelEntity();
        $Automovel->uuid($id);
        $Automovel->set(lista: $request->dado());
        $Automovel->salvar();
        return new Response(status: 204);
    }
}
