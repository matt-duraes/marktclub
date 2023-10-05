<?php

namespace App\Models\Site\Contato;

use Erro\Excecao;
use Http\Request;
use Helpers\ApiHelper;

final class ContatoModel extends ApiHelper
{
    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
    }

    /**
     * @return object|array
     * @throws Excecao
     */
    public function postSalvar(): object
    {
        $this
        ->validar('Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.')
        ->body([
            'nome'     => $this->request->nome,
            'telefone' => $this->request->telefone,
            'email'    => $this->request->email,
            'mensagem' => $this->request->mensagem,
        ])
        ->post('/contato')
        ->object();
        return mensagemSucesso([], 201);
    }
}
