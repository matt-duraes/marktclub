<?php

namespace Tests\Api;

use App\Classes\SolicitacaoDeclaracao\Tipo;
use Erro\Excecao;
use Tests\Tests;

class SolicitacaoDeclaracaoTest extends Tests
{
    private string $idSolicitacaoDeclaracao = 'abc58a05-14f8-49ee-bf9c-40cffeacc9d8';

    /**
     * @return SolicitacaoDeclaracaoTest
     * @throws Excecao
     */
    public function buscarSolicitacaoDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $this->api('solicitacao_declaracao:buscar');
        $this
            ->Curl
            ->get('/solicitacao-declaracao/' . $this->idSolicitacaoDeclaracao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idSolicitacaoDeclaracao);
    }

    /**
     * @return SolicitacaoDeclaracaoTest
     * @throws Excecao
     */
    public function listarSolicitacoesDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $this->api('solicitacao_declaracao:listar');
        $this
            ->Curl
            ->json([
                'pagina'           => 1,
                'ordem'            => '',
                'tipo'             => '',
                'status'           => '',
                'data_criacao_de'  => '',
                'data_criacao_ate' => ''
            ])
            ->get('/solicitacao-declaracao');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    /**
     * @return SolicitacaoDeclaracaoTest
     * @throws Excecao
     */
    public function salvarSimulacoesDeCreditoTest(): SolicitacaoDeclaracaoTest
    {
        $this->api('solicitacao_declaracao:salvar');
        $this
            ->Curl
            ->body([
                'url'  => 'parceiro-normal',
                'tipo' => Tipo::CONVENIO
            ])
            ->post('/solicitacao-declaracao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }
}
