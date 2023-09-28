<?php

namespace Tests\Api;

use App\Classes\Solicitacao\Status;
use Erro\Excecao;
use Tests\Token\Clube;

class SolicitacaoDeclaracaoTest extends Clube
{
    private string $idSolicitacaoDeclaracao;
    private string $idParceiro = '4502e7e8-9359-470e-9588-0a1501449675';

    /**
     * @throws Excecao
     */
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
                'pagina' => 1
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
                'parceiro' => $this->idParceiro
            ])
            ->post('/solicitacao-declaracao')
            ->array();

        $this->idSolicitacaoDeclaracao = $solicitacao['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.parceiro.id', $this->idParceiro);
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

    /**
     * @return SolicitacaoDeclaracaoTest
     * @throws Excecao
     */
    public function atualizarSolicitacaoDeDeclaracaoTest(): SolicitacaoDeclaracaoTest
    {
        $this
            ->Curl
            ->body([
                'status' => Status::FINALIZADO
            ])
            ->put('/solicitacao-declaracao/' . $this->idSolicitacaoDeclaracao);

        return $this
            ->checkStatus(204);
    }
}
