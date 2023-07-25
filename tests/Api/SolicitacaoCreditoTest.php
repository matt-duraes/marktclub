<?php

namespace Tests\Api;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Tipo;
use Erro\Excecao;
use Tests\Tests;

final class SolicitacaoCreditoTest extends Tests
{
    private string $idSolicitacaoCredito = 'c9c6d7cd-27d3-471c-b7e0-73fc7026368d';

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function realizarSimulacaoDeCreditoTest(): SolicitacaoCreditoTest
    {
        $this->api('solicitacao_credito:simular');
        $this
            ->Curl
            ->parametro([
                'operadora' => 'sicoob',
                'tipo'      => 'consignado',
                'valor'     => '10000.00',
                'parcelas'  => 96
            ])
            ->get('/solicitar-credito');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function buscarSimulacaoDeCreditoTest(): SolicitacaoCreditoTest
    {
        $this->api('solicitacao_credito:buscar');
        $this
            ->Curl
            ->get('/solicitacao-credito/' . $this->idSolicitacaoCredito);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idSolicitacaoCredito);
    }

    /**
     * @return SolicitacaoCreditoTest
     * @throws Excecao
     */
    public function listarSimulacoesDeCreditoTest(): SolicitacaoCreditoTest
    {
        $this->api('solicitacao_credito:listar');
        $this
            ->Curl
            ->json([
                'pagina'           => 1,
                'tipo'             => '',
                'status'           => '',
                'data_criacao_de'  => '',
                'data_criacao_ate' => ''
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
        $this->api('solicitacao_credito:salvar');
        $this
            ->Curl
            ->body([
                'operadora'      => Operadora::SICOOB,
                'tipo'           => Tipo::CONSIGNADO,
                'valor'          => '15000.00',
                'parcelas'       => 12,
                'valor_parcelas' => '',
                'status'         => ''
            ])
            ->post('/solicitacao-credito');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }
}
