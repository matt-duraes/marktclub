<?php

namespace App\Controllers\Api;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoDeclaracao\Ordem;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SolicitacaoDeclaracao\DeclaracaoEntity;
use App\Models\Api\SolicitacaoDeclaracao\DeclaracaoModel;
use App\Models\Api\SolicitacaoVoucher\DownloadModel;
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

class SolicitacaoDeclaracaoController extends Controller implements
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
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->uuid($id);
        return $this->retornoSucesso($Declaracao);
    }

    /**
     * @param DeclaracaoEntity $Declaracao
     * @param int              $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(DeclaracaoEntity $Declaracao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Declaracao, lista: [
                'uuid', 'empresa', 'usuario', 'parceiro', 'modelo',
                'versao', 'data_criacao', 'data_atualizacao', 'status'
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
        $Declaracao = new DeclaracaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->titulo,
            $request->empresa,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($Declaracao->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->set(lista: $request->dado());
        $Declaracao->salvar();
        return $this->retornoSucesso($Declaracao, 201);
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
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->uuid($id);
        $Declaracao->set(lista: $request->dado());
        $Declaracao->salvar();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDownload(Request $request): Response
    {
        $Voucher = new DownloadModel($request);
        $Download = new ArquivoEntity($Voucher->download(), $request->usuario);
        $Download->salvar();
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }
}
