<?php

namespace App\Models\Site\Sicoob;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use App\Helpers\ClubeApiHelper;

final class ContratacaoModel extends ClubeApiHelper
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
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(): Response
    {
        $this
            ->validar('Página não encontrada!', status: 404)
            ->body([
                'operadora' => 1,
                'tipo'      => $this->tipo,
                'valor'     => strDinheiro($this->valor),
                'parcelas'  => $this->prazo,
            ])->post('/solicitacao-credito')
            ->object();

        return mensagemSucesso([], 201);
    }
}
