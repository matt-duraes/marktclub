<?php

namespace PainelApp\atualizacao\Controllers;

use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;

final class AtualizacaoController extends Controller
{
    public function index(Request $request)
    {
        $noticia = (new ApiHelper(token: true))
            ->json([
                'publicado' => 'sim',
                'pagina'    => 1
            ])
            ->get('/publicacao-noticia')
            ->object();

        $lista = [];
        $pagina = (object)[
            'total'     => 0,
            'atual'     => 0,
            'paginacao' => []
        ];

        if (chaveExiste('dado.lista', $noticia)) {
            $lista = $noticia->dado->lista;
            $pagina = $noticia->dado->pagina;
        }

        return view('painel.atualizacao.index', [
            'lista'  => $lista,
            'pagina' => $pagina
        ]);
    }

    public function detalhe(string $url)
    {
        $noticia = (new ApiHelper(token: true))
            ->validar(status: 404)
            ->get('/publicacao-noticia/' . $url)
            ->object()->dado;
        if ($noticia->publicado != 'sim') {
            mensagemStatus(404);
        }
        return view('painel.atualizacao.detalhe', [
            'noticia' => $noticia
        ]);
    }
}
