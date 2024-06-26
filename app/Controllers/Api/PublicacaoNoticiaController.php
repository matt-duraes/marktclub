<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoNoticia\Tipo;
use App\Classes\PublicacaoNoticia\Ordem;
use App\Models\Api\PublicacaoNoticia\HomeModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\PublicacaoNoticia\NoticiaModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\PublicacaoNoticia\NoticiaEntity;

final class PublicacaoNoticiaController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Noticia = new NoticiaModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            pesquisa: $request->pesquisa,
            data_inicio_de: new Data($request->data_inicio_de),
            data_inicio_ate: new Data($request->data_inicio_ate),
            publicado: new Botao($request->publicado),
            home: new Botao($request->home),
            tipo: new Tipo($request->tipo),
            ordem: new Ordem($request->ordem),
            status: new Status($request->status),
            restrita: new Botao($request->restrita),
            site: new Botao($request->site),
        );
        return mensagemSucesso($Noticia->listarDados());
    }

    public function getHome()
    {
        $Noticia = $Noticia = new HomeModel();
        return mensagemSucesso($Noticia->noticia);
    }

    public function getBuscar(string $id): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->idSlug($id);
        return $this->retornoSucesso($Noticia);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto_grande'] = $request->getPost('texto_grande', html: false);
        $Noticia = new NoticiaEntity();
        $Noticia->set(lista: $dado);
        $Noticia->salvar();

        return $this->retornoSucesso($Noticia, 201);
    }

    private function retornoSucesso(NoticiaEntity $Noticia, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Noticia,
                lista: [
                    'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande',
                    'texto_pequeno', 'imagem_grande', 'imagem_pequena', 'imagem_galeria',
                    'imagem_social', 'arquivo', 'fonte_noticia', 'fonte_link', 'autor_noticia',
                    'url', 'data_inicio', 'data_final', 'data_atualizada', 'permissao_restrita',
                    'permissao_site', 'status', 'header_titulo', 'header_descricao', 'header_tag',
                    'tipo', 'home', 'publicado'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto_grande')) {
            $dado['texto_grande'] = $request->getPut('texto_grande', html: false);
        }

        $Noticia = new NoticiaEntity();
        $Noticia->idSlug($id);
        $Noticia->set(lista: $dado);
        $Noticia->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->uuid($id);
        $Noticia->destruir();

        return new Response(status: 204);
    }
}
