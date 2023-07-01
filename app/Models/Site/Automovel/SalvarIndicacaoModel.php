<?php

namespace App\Models\Site\Automovel;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;
use Http\Response;

final class SalvarIndicacaoModel
{
    protected string $veiculo;
    protected string $modelo;
    protected string $versao;
    protected string $cor;
    protected string $cidade;
    protected string $mensagem;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        $this->veiculo = $request->veiculo;
        $this->modelo = $request->modelo;
        $this->versao = $request->versao;
        $this->cor = $request->cor;
        $this->cidade = $request->cidade;
        $this->mensagem = $request->mensagem;
    }


    /**
     * @return object|array
     * @throws Excecao
     */
    public function postSalvar(): object
    {
        $api = new ApiHelper('mensagem_indicacao_automovel:salvar');

        $api->body([
                'produto' => $this->veiculo,
                'modelo' => $this->modelo,
                'versao' => $this->versao,
                'cor' => $this->cor,
                'cidade' => $this->cidade,
                'mensagem' => $this->mensagem
            ])->post('/automovel/indicacao')
            ->object();

        return mensagemSucesso([], 201);
    }



}
