<?php

namespace Tests\Api;

use App\Classes\Solicitacao\Status;
use Tests\Api\Token\Clube;

class SolicitacaoAutomovelTest extends Clube
{
    private string $idSolicitacao;
    private string $statusValido;

    public function __construct()
    {
        $this->pegarToken();
        $this->pegarStatusValido();
        parent::__construct();
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'endereco_estado' => estadoAleatorio(),
            'endereco_cidade' => cidadeAleatorio(),
            'montadora'       => 'Fiat',
            'modelo'          => 'Uno',
            'versao'          => '1.0',
            'cor'             => 'Branco',
            'mensagem'        => 'Gostaria de um orçamento para o conserto do meu carro',
        ], $array);
    }

    private function pegarStatusValido(): void
    {
        $this->statusValido = valorAleatorio(array_keys((new Status())->select()));
    }

    public function listarSolicitacoesAutomovelTest(): SolicitacaoAutomovelTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/solicitacao-automovel');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarNovaSolicitacaoValidaTest(): SolicitacaoAutomovelTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/solicitacao-automovel')
            ->array();

        $this->idSolicitacao = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.status', 'novo')
            ->checkIndiceExiste('dado.id');
    }

    public function atualizarStatusSolicitacaoTest(): SolicitacaoAutomovelTest
    {
        $this
            ->Curl
            ->body([
                'status' => $this->statusValido
            ])
            ->put('/solicitacao-automovel/' . $this->idSolicitacao);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAtualizarComStatusInvalidoTest(): SolicitacaoAutomovelTest
    {
        $this
            ->Curl
            ->body([
                'status' => 'STATUS INVALIDO'
            ])
            ->put('/solicitacao-automovel/' . $this->idSolicitacao);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    public function buscarPorIdTest(): SolicitacaoAutomovelTest
    {
        $this
            ->Curl
            ->get('/solicitacao-automovel/' . $this->idSolicitacao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.status', $this->statusValido)
            ->checkIndiceExiste('dado.id');
    }
}
