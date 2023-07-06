<?php

namespace App\Models\Site\Sicoob;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;

final class SimulacaoModel
{
    protected string $tipo;
    protected string $valor;
    protected int $prazo;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        $this->tipo = $request->tipo;
        $this->valor = $request->valor;
        $this->prazo = $request->prazo;
    }

    /**
     * @return object|array
     * @throws Excecao
     */
    public function getSimulacao(): object
    {
        $api = new ApiHelper('solicitacao_credito:simular');

        $dado = $api->validar('Página não encontrada!', status: 404)->parametro([
            'operadora' => 1,
            'tipo'      => $this->tipo,
            'valor'     => $this->valor,
            'parcelas'  => $this->prazo,
        ])->get('/solicitar-credito')
            ->object();

        return mensagemSucesso([
            'lista' => $this->montarRetorno($dado->dado)
        ]);
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        $retorno = [];
        if ($dado) {
            $retorno = (object)[
                'valor'          => $dado->valor,
                'parcelas'       => $dado->parcelas,
                'valor_parcelas' => $dado->valor_parcelas,
                'tipo'           => $dado->tipo
            ];
        }

        return $retorno;
    }
}
