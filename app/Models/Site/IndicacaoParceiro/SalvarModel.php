<?php

namespace App\Models\Site\IndicacaoParceiro;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;
use Http\Response;

final class SalvarModel
{
    protected string $parceiro;
    protected string $telefone;
    protected string $email;
    protected string $mensagem;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        $this->parceiro = $request->parceiro;
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
        $api = new ApiHelper('mensagem_indicacao_parceiro:salvar');

        $api->body([
                'parceiro' => $this->parceiro,
                'telefone' => $this->telefone,
                'email' => $this->email,
                'mensagem' => $this->mensagem,
                'tipo' => 2
            ])->post('/parceiro/indicacao')
            ->object();

        return mensagemSucesso([], 201);
    }



}
