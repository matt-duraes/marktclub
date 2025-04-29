<?php

namespace App\Controllers\Api;

use App\Classes\Pesquisa\Ordem;
use App\Models\Api\Pesquisa\PesquisaEntity;
use App\Models\Api\Pesquisa\PesquisaModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class PesquisaController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $PesquisaEntity = new PesquisaEntity();
        $PesquisaEntity->uuid($id);
        return $this->retornoSucesso($PesquisaEntity);
    }

    /**
     * @param PesquisaEntity $pesquisaEntity
     * @param int            $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(PesquisaEntity $pesquisaEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($pesquisaEntity, lista: [
            'fidelidade', 'produtos', 'gasto', 'importancia', 'cashback',
            'frequencia', 'resgate', 'desconto', 'experiencia', 'indicaria',
            'data_criacao', 'data_atualizacao'
        ]), $status);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $PesquisaEntity = new PesquisaEntity();
        $PesquisaEntity->set(lista: $request->dado());
        $PesquisaEntity->salvar();
        return $this->retornoSucesso($PesquisaEntity, 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $PesquisaModel = new PesquisaModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem)
        );
        return mensagemSucesso($PesquisaModel->listarDados());
    }
}
