<?php

namespace Tests\Api;

use Erro\Excecao;
use Modules\Botao;
use Tests\Api\Token\Clube;

class SolicitacaoDeclaracaoTest extends Clube
{
    private string $idSolicitacaoDeclaracao;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    /**
     * @return SolicitacaoDeclaracaoTest
     * @throws Excecao
     */
    public function listarSolicitacoesDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $this
            ->Curl
            ->json([
                'pagina'           => 1,
                'publicado'        => valorAleatorio([Botao::NAO, Botao::SIM])
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
    public function salvarSolicitacaoDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $solicitacao = $this
            ->Curl
            ->body([
                'parceiro' => '4502e7e8-9359-470e-9588-0a1501449675'
            ])
            ->post('/solicitacao-declaracao')
            ->array();

        $this->idSolicitacaoDeclaracao = $solicitacao['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SolicitacaoDeclaracaoTest
     * @throws Excecao
     */
    public function buscarSolicitacaoDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $this
            ->Curl
            ->get('/solicitacao-declaracao/' . $this->idSolicitacaoDeclaracao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idSolicitacaoDeclaracao);
    }
}
