<?php

namespace App\Controllers\Api;

use App\Classes\Geral\Status;
use App\Classes\PainelTradutor\Ordem;
use App\Helpers\GoogleTradutorHelper;
use App\Models\Api\Tradutor\TradutorEntity;
use App\Models\Api\Tradutor\TradutorModel;
use Controller\Controller;
use Erro\Excecao;
use Google\Cloud\Core\Exception\ServiceException;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class TradutorController extends Controller implements
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
        $TradutorEntity = new TradutorEntity();
        $TradutorEntity->uuid($id);
        return $this->retornoPadrao($TradutorEntity);
    }

    /**
     * @param TradutorEntity $tradutorEntity
     * @param int            $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(TradutorEntity $tradutorEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($tradutorEntity, lista: [
            'termo', 'traducao_en', 'traducao_es', 'traducao',
            'status', 'data_criacao', 'data_atualizacao'
        ]), $status);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $TradutorModel = new TradutorModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Status($request->status)
        );
        return mensagemSucesso($TradutorModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $TradutorEntity = new TradutorEntity();
        $TradutorEntity->set(lista: $request->dado());
        $TradutorEntity->salvar();
        return $this->retornoPadrao($TradutorEntity, 201);
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
        $TradutorEntity = new TradutorEntity();
        $TradutorEntity->uuid($id);
        $TradutorEntity->set(lista: $request->dado());
        $TradutorEntity->salvar();
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
        $TradutorEntity = new TradutorEntity();
        $TradutorEntity->uuid($id);
        $TradutorEntity->destruir();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     * @throws ServiceException
     */
    public function postTraduzir(Request $request): Response
    {
        $TradutorHelper = new GoogleTradutorHelper();
        return mensagemSucesso([
            'traducao' => [
                'en' => $TradutorHelper->traduzir($request->texto),
                'es' => $TradutorHelper->traduzir($request->texto, 'es')
            ]
        ]);
    }
}
