<?php

namespace App\Models\Site\Sicoob;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;

final class ContratacaoModel
{
    protected string $tipo;
    protected string $valor;
    protected int $prazo;
    protected string $operadora;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        $this->tipo = $request->tipo;
        $this->valor = $request->valor;
        $this->prazo = $request->prazo;
        $this->operadora = $request->operadora;
    }

    /**
     * @return object|array
     * @throws Excecao
     */
    public function postSalvar(): object
    {
        $api = new ApiHelper('solicitacao_credito:salvar');

        $api->validar('Página não encontrada!', status: 404)->body([
            'operadora' => 1,
            'tipo'      => $this->tipo,
            'valor'     => strDinheiro($this->valor),
            'parcelas'  => $this->prazo,
        ])->post('/solicitacao-credito')
            ->object();

        return mensagemSucesso([], 201);
    }
}
