<?php

namespace Painel\Historico\Route;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Painel\Historico\Models\Entity;
use App\Controllers\Painel\PadraoController as ControllerSystem;

final class Controller extends ControllerSystem
{
    public function postIndex(Request $request)
    {
        $this->validarSeEstaLogado();
        return view(
            arquivo: 'historico',
            var: [
                'id' => sessao('HISTORICO_ID'),
                'location' => $request->chave('location', 1)
            ]
        );
    }

    public function putSalvar(Request $request)
    {
        if (!$request->existe('id') || empty($request->id)) {
            throw new Excecao(status: 400);
        }

        $Historico = new Entity();
        $Historico->id($request->id);
        $Historico->set('texto', $request->texto);
        $Historico->salvar();

        sessaoDeletar('HISTORICO_ID');

        return new Response(status: 204);
    }
}
