<?php

namespace Tests\Api;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Tipo;
use Erro\Excecao;
use Tests\Token\Clube;

final class SolicitacaoCreditoTest extends Clube
{
    private string $idSolicitacaoCredito;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function realizarSimulacaoDeCreditoTest(): SolicitacaoCreditoTest
    {
        $this
            ->Curl
            ->json([
                'operadora'   => Operadora::SICOOB,
                'tipo'        => Tipo::CONSIGNADO,
                'valor_total' => number_format($this->numero(), 2, thousands_separator: ''),
                'parcela'     => $this->numero(1, 96)
            ])
            ->get('/solicitacao-credito/simulacao');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function listarSimulacoesDeCreditoTest(): SolicitacaoCreditoTest
    {
        $this
            ->Curl
            ->json([
                'pagina'           => 1,
                'tipo'             => '',
                'status'           => '',
                'data_inicio'      => '',
                'data_final'       => ''
            ])
            ->get('/solicitacao-credito');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function salvarSimulacoesDeCreditoTest(): SolicitacaoCreditoTest
    {
        $simulacao = $this
            ->Curl
            ->body([
                'operadora'   => Operadora::SICOOB,
                'tipo'        => Tipo::CONSIGNADO,
                'valor_total' => number_format($this->numero(1, 50000), 2, thousands_separator: ''),
                'parcela'     => 12
            ])
            ->post('/solicitacao-credito')
            ->array();

        $this->idSolicitacaoCredito = $simulacao['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function buscarSimulacaoDeCreditoTest(): SolicitacaoCreditoTest
    {
        $this
            ->Curl
            ->get('/solicitacao-credito/' . $this->idSolicitacaoCredito);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idSolicitacaoCredito);
    }
}
