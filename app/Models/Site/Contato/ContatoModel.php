<?php

namespace App\Models\Site\Contato;

use Erro\Excecao;
use Helpers\ApiHelper;

final class ContatoModel extends ApiHelper
{
    /**
     * @return object|array
     * @throws Excecao
     */
    public function enviarContato($request): object
    {
        $this
            ->body([
                'nome'     => $request->nome,
                'telefone' => $request->telefone,
                'email'    => $request->email,
                'mensagem' => $request->mensagem,
                'url'      => LINK_SITE
            ])
            ->post('/solicitacao-contato')
            ->object();
        return mensagemSucesso([], 201);
    }
}
