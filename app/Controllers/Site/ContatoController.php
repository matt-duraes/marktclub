<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Contato\ContatoModel;

final class ContatoController extends Controller
{
    /**
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        return view(
            'contato.index'
        );
    }

    public function postContatoLogin(Request $request): Response
    {
        (new ContatoModel())->enviarContato($request);

        return mensagemSucesso([], status: 201);
    }
}
