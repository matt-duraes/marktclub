<?php

namespace Tests\Api;

use App\Classes\Geral\Publicado;
use Erro\Excecao;
use Modules\Botao;
use Tests\Api\Token\Clube;

class SolicitacaoDeclaracaoTest extends Clube
{
    private string $idSolicitacaoDeclaracao;

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
        $this->api('solicitacao_declaracao:salvar');
        $solicitacao = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'parceiro' => '4502e7e8-9359-470e-9588-0a1501449675'
            ])
            ->post('/solicitacao-declaracao')
            ->array()['dado'] ?? [];

        $this->idSolicitacaoDeclaracao = $solicitacao['id'] ?? '';

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
        $this->api('solicitacao_declaracao:buscar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/solicitacao-declaracao/' . $this->idSolicitacaoDeclaracao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idSolicitacaoDeclaracao);
    }
}
