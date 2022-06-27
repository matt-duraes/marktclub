<?php

namespace Painel\Album\Controllers;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Painel\Album\Models\AlbumDadoModel;
use Painel\Album\Models\AlbumDadoEntity;

final class AlbumController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX/DETALHE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        painelPermissao('album_index');
        $Album = new AlbumDadoModel();

        $permissao = sessao('USUARIO.permissao');
        return view('painel.album.Views.album_index', [
            'appTitulo' => 'ÁLBUM',
            'app' => 'album',
            'album' => $Album->listarAlbuns(),
            'check' => in_array('album_editar', $permissao) || in_array('album_deletar', $permissao)
        ]);
    }

    public function postDeletar(Request $request)
    {
        painelPermissao('album_deletar');
        foreach ($request->id as $album) {
            $AlbumEntity = new AlbumDadoEntity();
            $AlbumEntity->id($album);
            $AlbumEntity->destruir();
        }

        return new Response(status: 204);
    }
}
