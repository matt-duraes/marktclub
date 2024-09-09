<?php

namespace App\Controllers\Api;

use App\Classes\Geral\Status;
use App\Classes\PublicacaoArquivo\Ordem;
use App\Classes\PublicacaoArquivo\Tipo;
use App\Models\Api\PublicacaoArquivo\ArquivoEntity;
use App\Models\Api\PublicacaoArquivo\ArquivoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class PublicacaoArquivoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id UUID
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $ArquivoEntity = new ArquivoEntity();
        $ArquivoEntity->uuid($id);
        return $this->retornoPadrao($ArquivoEntity);
    }

    /**
     * @param ArquivoEntity $arquivoEntity
     * @param int           $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(ArquivoEntity $arquivoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($arquivoEntity, lista: [
            'titulo', 'texto', 'tipo', 'data_inicio', 'data_final', 'permissao_restrita',
            'permissao_site', 'imagem', 'arquivo', 'publicado', 'status'
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
        $ArquivoModel = new ArquivoModel(
            new Pagina($request->getJson('pagina')),
            new Quantidade($request->getJson('quantidade')),
            new Ordem($request->getJson('ordem')),
            $request->getJson('pesquisa'),
            new Tipo($request->getJson('tipo')),
            new Botao($request->getJson('site')),
            new Botao($request->getJson('restrita')),
            new Botao($request->getJson('publicado')),
            new Data($request->getJson('data_inicio')),
            new Data($request->getJson('data_final')),
            new Status($request->getJson('status'))
        );
        return mensagemSucesso($ArquivoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $ArquivoEntity = new ArquivoEntity();
        $ArquivoEntity->set(lista: $request->dado());
        $ArquivoEntity->salvar();
        return $this->retornoPadrao($ArquivoEntity, 201);
    }

    /**
     * @param Request $request
     * @param string  $id UUID
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $ArquivoEntity = new ArquivoEntity();
        $ArquivoEntity->uuid($id);
        $ArquivoEntity->set(lista: $request->dado());
        $ArquivoEntity->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id UUID
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $ArquivoEntity = new ArquivoEntity();
        $ArquivoEntity->uuid($id);
        $ArquivoEntity->destruir();
        return new Response(status: 204);
    }
}
