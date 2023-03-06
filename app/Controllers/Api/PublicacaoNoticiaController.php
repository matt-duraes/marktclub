<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\PublicacaoNoticia\NoticiaModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\PublicacaoNoticia\NoticiaEntity;

final class PublicacaoNoticiaController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $Noticia = new NoticiaEntity;
        $Noticia->id($id);

        return $this->retornoSucesso($Noticia);
    }

    public function getListar(Request $request): Response
    {
        $Noticia = new NoticiaModel($request);
        $dado = $Noticia->listarDados();

        return mensagemSucesso($dado);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto_grande'] = $request->_POST('texto_grande', html: false);

        $Noticia = new NoticiaEntity();
        $Noticia->set(lista: $dado);
        $Noticia->salvar();

        return $this->retornoSucesso($Noticia, 201);
    }

    private function retornoSucesso(NoticiaEntity $Noticia, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Noticia,
                lista: [
                    'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande', 'texto_pequeno',
                    'imagem_grande', 'imagem_pequena', 'imagem_galeria', 'imagem_social', 'arquivo',
                    'fonte_noticia', 'fonte_link', 'autor_noticia', 'data_publicacao_inicio',
                    'data_publicacao_final', 'data_publicacao_atualizacao', 'permissao_restrita',
                    'permissao_site', 'permissao_banner', 'url', 'status'
                ],
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if ($request->existe('texto_grande')) {
            $dado['texto_grande'] = $request->_PUT('texto_grande', html: false);
        }

        $Noticia = new NoticiaEntity();
        $Noticia->id($id);
        $Noticia->set(lista: $dado);
        $Noticia->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->id($id);
        $Noticia->destruir();

        return new Response(status: 204);
    }
}
