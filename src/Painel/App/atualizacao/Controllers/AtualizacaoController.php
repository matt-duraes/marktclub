<?php

namespace PainelApp\atualizacao\Controllers;

use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\PublicacaoNoticia\Tipo;

final class AtualizacaoController extends Controller
{
    public function index(Request $request)
    {
        $pagina = $request->pagina;
        $noticia = (new ApiHelper(token: true))
            ->json([
                'tipo' => Tipo::PAINEL,
                'publicado'  => 'sim',
                'pagina'     => !empty($pagina) && preg_match('/^[1-9]{1}[0-9]{0,}$/', $pagina) ? $pagina : 1,
                'quantidade' => 10
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
