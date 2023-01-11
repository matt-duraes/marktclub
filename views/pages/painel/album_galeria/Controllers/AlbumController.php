<?php

namespace Painel\AlbumGaleria\Controllers;

use Http\Request;
use Http\Response;
use Controller\Controller;

final class AlbumController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX/DETALHE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // painelPermissao('album_index');
        // $Album = new AlbumDadoModel();

        $permissao = sessao('USUARIO.permissao');
        return view('painel.album_galeria.index', [
            'appTitulo' => 'ÁLBUM',
            'app' => 'album_galeria',
            'album' => [],
            'podeDeletar' => in_array('album_galeria_deletar', $permissao)
        ]);
    }

    public function postDeletar(Request $request)
    {
        // painelPermissao('album_deletar');
        // foreach ($request->id as $album) {
        //     // $AlbumEntity = new AlbumDadoEntity();
        //     $AlbumEntity->id($album);
        //     $AlbumEntity->destruir();
        // }

        return new Response(status: 204);
    }
}
