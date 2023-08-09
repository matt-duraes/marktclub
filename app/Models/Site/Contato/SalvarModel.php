<?php

namespace App\Models\Site\Contato;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;
use App\Classes\Contato\Tipo;

final class SalvarModel extends ApiHelper
{
    protected string $nome;
    protected string $telefone;
    protected string $email;
    protected string $mensagem;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        $this->nome = $request->nome;
        $this->telefone = $request->telefone;
        $this->email = $request->email;
        $this->mensagem = $request->mensagem;
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
            'nome'     => $this->nome,
            'telefone' => $this->telefone,
            'email'    => $this->email,
            'mensagem' => $this->mensagem,
            'tipo'     => new Tipo(Tipo::SEM_AUTENTICACAO),
        ])
        ->post('/contato')
        ->object();

        return mensagemSucesso([], 201);
    }
}
