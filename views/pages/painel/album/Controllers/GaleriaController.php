<?php

namespace Painel\Album\Controllers;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Painel\Album\Models\AlbumDadoEntity;
use Painel\Album\Models\AlbumArquivoModel;
use Painel\Album\Models\AlbumArquivoEntity;

final class GaleriaController extends Controller
{
    /**
     * Index
     */
    public function index(string $uuid)
    {
        painelPermissao('album_index');
        $Album = new AlbumDadoEntity();
        $Album->id($uuid);

        return view('painel.album.galeria_index', [
            'id' => $uuid,
            'appTitulo' => $Album->titulo,
            'appVoltar' => [route('album.index'), 'ÁLBUM'],
            'app' => 'foto',
            'permissao' => (object)[
                'add' => painelPermissao('foto_add', false),
                'editar' => painelPermissao('foto_editar', false),
                'deletar' => painelPermissao('foto_deletar', false),
                'ordem' => painelPermissao('foto_ordem', false),
            ]
        ]);
    }

    /**
     * Pega as imagens que já foram feito o upload
     */
    public function postImagem(Request $request)
    {
        painelPermissao('album_index');
        $Album = new AlbumDadoEntity();
        $Album->id($request->id);

        $Galeria = new AlbumArquivoModel();
        $dado = $Galeria->listarImagens($Album->get('id'), $request->pagina);

        return new Response(json: [
            'lista' => $dado->lista,
            'paginacao' => $dado->pagina->atual < $dado->pagina->total,
        ]);
    }

    /**
     * Envia imagem para upload
     */
    public function postUpload(Request $request)
    {
        painelPermissao('foto_add');
        $Arquivo = new AlbumArquivoEntity(
            album: $request->id,
            arquivo: $request->arquivo,
            capa: $request->capa
        );
        $Arquivo->salvar();

        return new Response(json: [
            'id' => $Arquivo->id,
            'titulo' => $Arquivo->titulo,
            'imagem' => $Arquivo->get('imagem'),
        ], status: 201);
    }

    /**
     * Faz o download de uma imagem
     */
    public function download($id)
    {
        painelPermissao('album_index');
        $Arquivo = new AlbumArquivoEntity();
        $Arquivo->id($id);

        return new Response(download: DIRETORIO_PRIVADO . '/album/' . $Arquivo->imagem);
    }

    /**
     * Edita uma imagem
     */
    public function postEditar(Request $request)
    {
        painelPermissao('foto_editar');
        $Arquivo = new AlbumArquivoEntity();
        $Arquivo->id($request->id);

        return view('painel.album.galeria_editar', [
            'id' => $request->id,
            'album' => $request->album,
            'titulo' => $Arquivo->titulo
        ]);
    }

    /**
     * Salva a edição da imagem
     */
    public function putEditar(Request $request)
    {
        painelPermissao('foto_editar');
        if (empty($request->titulo)) {
            mensagemErro('Erro!', 'Você precisa enviar um título para a imagem.');
        }

        try {
            $Album = new AlbumDadoEntity();
            $Album->id($request->album);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi possível encontrar o álbum dessa imagem.');
        }

        try {
            $Arquivo = new AlbumArquivoEntity();
            $Arquivo->buscar([
                ['uuid', $request->id],
                ['id_album_dado', $Album->get('id')]
            ]);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'O arquivo enviado não foi encontrado.');
        }

        if ($Arquivo->titulo != $request->titulo) {
            $Arquivo->titulo = $request->titulo;
            $Arquivo->salvar();
        }
        if ($request->capa) {
            $Album->imagem = $Arquivo->imagem;
            $Album->salvar();
        }
        return new Response(status: 204);
    }

    public function postDeletar(Request $request)
    {
        painelPermissao('foto_deletar');

        try {
            $Album = new AlbumDadoEntity();
            $Album->id($request->album);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi possível encontrar o álbum.');
        }

        $idAlbum = $Album->get('id');
        foreach ($request->id as $imagem) {
            $ArquivoEntity = new AlbumArquivoEntity();
            $ArquivoEntity->buscar([
                ['uuid', $imagem],
                ['id_album_dado', $idAlbum]
            ]);
            $ArquivoEntity->destruir();
        }

        return new Response(status: 204);
    }

    public function postOrdem(Request $request)
    {
        painelPermissao('foto_ordem');
        try {
            $Album = new AlbumDadoEntity();
            $Album->id($request->album);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi possível encontrar o álbum.');
        }

        $idAlbum = $Album->get('id');
        $ordem = 1;
        foreach ($request->id as $imagem) {
            $ArquivoEntity = new AlbumArquivoEntity();
            $ArquivoEntity->buscar([
                ['uuid', $imagem],
                ['id_album_dado', $idAlbum]
            ]);
            $ArquivoEntity->ordem = $ordem;
            $ArquivoEntity->salvar();
            $ordem++;
        }

        return new Response(status: 204);
    }
}
