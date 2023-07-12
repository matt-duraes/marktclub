<?php

namespace App\Models\Site\Sicoob;

use stdClass;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use App\Helpers\ClubeApiHelper;

final class SimulacaoModel extends ClubeApiHelper
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
     * @return Response
     * @throws Excecao
     */
    public function getSimulacao(): Response
    {
        $dado = $this
            ->validar('Página não encontrada!', status: 404)
            ->parametro([
                'operadora' => 'sicoob-judiciario',
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
     * @param  stdClass $dado
     * @return stdClass
     */
    private function montarRetorno(stdClass $dado): stdClass
    {
        return (object)[
            'valor'          => $dado->valor,
            'parcelas'       => $dado->parcelas,
            'valor_parcelas' => $dado->valor_parcelas,
            'tipo'           => $dado->tipo
        ];
    }
}
