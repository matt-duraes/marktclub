<?php

namespace App\Models\Site\Contato;

use Erro\Excecao;
use Helpers\ApiHelper;

final class ContatoModel extends ApiHelper
{
    public function __construct()
    {
        parent::__construct(scope: 'solicitacao_contato:salvar');
    }

    /**
     * @return object|array
     * @throws Excecao
     */
    public function enviarContato($request): object
    {
        $this
            ->body([
                'nome'       => $request->nome,
                'telefone'   => $request->telefone,
                'email'      => $request->email,
                'mensagem'   => $request->mensagem,
                'tipo'       => 'clube contato',
                'local'      => LINK_SITE
            ])
            ->post('/solicitacao-contato')
            ->object();

        return mensagemSucesso([], 201);
    }
}
