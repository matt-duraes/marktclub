<?php

namespace App\Controllers\Api;

use App\Classes\Automovel\Versao\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\Automovel\Versao\VersaoEntity;
use App\Models\Api\Automovel\Versao\VersaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class AutomovelVersaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Versao = new VersaoEntity();
        $Versao->uuid($id);
        return $this->retornoSucesso($Versao);
    }

    /**
     * @param VersaoEntity $Versao
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(VersaoEntity $Versao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Versao, lista: [
                'titulo', 'imagem', 'imagemUrl', 'cor', 'valor_de', 'valor_por', 'status',
                'data_criacao', 'data_atualizacao'
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
        $Versao = new VersaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->parceiro,
            $request->modelo,
            new Status($request->status)
        );
        return mensagemSucesso($Versao->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Versao = new VersaoEntity();
        $Versao->set(lista: $request->dado());
        $Versao->salvar();
        return $this->retornoSucesso($Versao, 201);
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
        $Versao = new VersaoEntity();
        $Versao->uuid($id);
        $Versao->set(lista: $request->dado());
        $Versao->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Versao = new VersaoEntity();
        $Versao->uuid($id);
        $Versao->destruir();
        return new Response(status: 204);
    }
}
