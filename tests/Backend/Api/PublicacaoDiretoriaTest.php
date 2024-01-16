<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use Tests\Tests;

class PublicacaoDiretoriaTest extends Tests
{
    protected string $scope = 'publicacao_diretoria';
    protected string $uri = '/publicacao-diretoria';
    public string $automatico = 'crud';

    public function naoPodeSalvarComStatusInvalidoTest(): PublicacaoDiretoriaTest
    {
        $this->api('publicacao_diretoria:salvar');
        $this
            ->Curl
            ->body($this->pegarBody([
                'status' => 'STATUS INVALIDO'
            ]))
            ->post('/publicacao-diretoria');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    public function naoPodeSalvarComNomeVaziosTest(): PublicacaoDiretoriaTest
    {
        $this->api('publicacao_diretoria:salvar');
        $this
            ->Curl
            ->body($this->pegarBody([
                'nome'   => ''
            ]))
            ->post('/publicacao-diretoria');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Nome não pode ser vazio.');
    }

    protected function pegarBody(array $array = []): array
    {
        return array_merge([
            'nome'   => nomeCompletoAleatorio(),
            'texto'  => 'Texto da publicação',
            'cargo'  => 'Cargo',
            'imagem' => 'imagem',
            'status' => valorAleatorio(array_keys((new Status())->select()))
        ], $array);
    }
}
