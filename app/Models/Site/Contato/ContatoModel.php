<?php

namespace App\Models\Site\Contato;

use Erro\Excecao;
use Http\Request;
use Helpers\ApiHelper;

final class ContatoModel extends ApiHelper
{

    /**
     * @return object|array
     * @throws Excecao
     */
    public function enviarContato($request): object
    {
        $teste = $this
            ->validar('Não foi possível enviar o formulário', status: 400)
            ->body([
                'nome'     => $request->nome,
                'telefone' => $request->telefone,
                'email'    => $request->email,
                'mensagem' => $request->mensagem,
                'url' => LINK_SITE
            ])
            ->post('/solicitacao-contato')
            ->object();

        ppe($teste);
        return mensagemSucesso([], 201);

    }
}
