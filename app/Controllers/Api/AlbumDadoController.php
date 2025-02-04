<?php

namespace App\Controllers\Api;

use App\Classes\AlbumDado\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\AlbumDado\AlbumEntity;
use App\Models\Api\AlbumDado\AlbumModel;
use Controller\Controller;
use Erro\Excecao;
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

class AlbumDadoController extends Controller implements
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
        $AlbumEntity = new AlbumEntity();
        $AlbumEntity->idSlug($id, mensagem: 'Álbum não encontrado ou inexistente');
        return $this->retornoPadrao($AlbumEntity);
    }

    /**
     * @param AlbumEntity $albumEntity
     * @param int         $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(AlbumEntity $albumEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($albumEntity, lista: [
            'titulo', 'texto', 'url', 'imagem', 'permissao_restrita',
            'permissao_site', 'data_inicio', 'data_final', 'foto', 'diretorio',
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
        $AlbumModel = new AlbumModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->pesquisa,
            $request->empresa,
            $request->equipe,
            $request->titulo,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($AlbumModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $AlbumEntity = new AlbumEntity();
        $AlbumEntity->set(lista: $request->dado());
        $AlbumEntity->salvar();
        return $this->retornoPadrao($AlbumEntity, 201);
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
        $AlbumEntity = new AlbumEntity();
        $AlbumEntity->uuid($id, mensagem: 'Álbum não encontrado ou inexistente');
        $AlbumEntity->set(lista: $request->dado());
        $AlbumEntity->salvar();
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
        $AlbumEntity = new AlbumEntity();
        $AlbumEntity->uuid($id, mensagem: 'Álbum não encontrado ou inexistente');
        $AlbumEntity->destruir();
        return new Response(status: 204);
    }
}
