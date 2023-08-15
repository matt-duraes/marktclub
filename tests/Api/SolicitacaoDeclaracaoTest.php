<?php

namespace Tests\Api;

use App\Classes\SolicitacaoDeclaracao\Tipo;
use Erro\Excecao;
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
    public function salvarSolicitacaoDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $this->api('solicitacao_declaracao:salvar');
        $solicitacao = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'url'  => 'parceiro-normal',
                'tipo' => Tipo::CONVENIO
            ])
            ->post('/solicitacao-declaracao')
            ->array()['dado'] ?? [];

        $this->idSolicitacaoDeclaracao = $solicitacao['id'];

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
            ->get('/solicitacao-declaracao/' . $this->idSolicitacaoDeclaracao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idSolicitacaoDeclaracao);
    }
}
