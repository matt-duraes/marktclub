<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\EnqueteMercado\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\EnqueteMercado\EnqueteMercadoModel;
use App\Models\Api\EnqueteMercado\EnqueteMercadoEntity;

class EnqueteMercadoController extends Controller implements
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
        $EnqueteMercadoEntity = new EnqueteMercadoEntity();
        $EnqueteMercadoEntity->uuid($id);
        return $this->retornoSucesso($EnqueteMercadoEntity);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $EnqueteMercadoEntity = new EnqueteMercadoEntity();
        $EnqueteMercadoEntity->set(lista: $request->dado());
        $EnqueteMercadoEntity->salvar();
        return $this->retornoSucesso($EnqueteMercadoEntity, 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $EnqueteMercadoModel = new EnqueteMercadoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem)
        );
        return mensagemSucesso($EnqueteMercadoModel->listarDados());
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function postRespondeu(): Response
    {
        $EnqueteMercadoEntity = new EnqueteMercadoEntity();
        return mensagemSucesso([
            'respondeu' => $EnqueteMercadoEntity->existeResposta() ? 'sim' : 'nao'
        ]);
    }

    /**
     * @param EnqueteMercadoEntity $enqueteMercadoEntity
     * @param int                  $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(EnqueteMercadoEntity $enqueteMercadoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($enqueteMercadoEntity, lista: [
            'fidelidade', 'produtos', 'gasto', 'importancia', 'cashback',
            'frequencia', 'resgate', 'desconto', 'experiencia', 'indicaria',
            'data_criacao', 'data_atualizacao'
        ]), $status);
    }
}
