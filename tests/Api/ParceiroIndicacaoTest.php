<?php

namespace Tests\Api;

use App\Classes\ParceiroIndicacao\Status;
use Tests\Api\Token\Clube;

class ParceiroIndicacaoTest extends Clube
{
    private array $statusValidos;
    private string $idIndicacaoNovoParceiro;

    public function __construct()
    {
        parent::__construct();
        $this->statusValidos = array_keys((new Status())->select());
    }

    public function salvarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'nome'              => $this->nomeCompleto(),
                'email'             => $this->email(),
                'telefone'          => $this->telefone(),
                'mensagem'          => 'Mensagem de teste',
                'status'            => valorAleatorio($this->statusValidos)
            ])
            ->post('/parceiro-indicacao')
            ->array();

        $this->idIndicacaoNovoParceiro = $dado['dado']['id'] ?? '';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function salvarIndidicacaoNovoParceiroSemNomeTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'email'             => $this->email(),
                'telefone'          => $this->telefone(),
                'mensagem'          => 'Mensagem de teste',
                'status'            => valorAleatorio($this->statusValidos)
            ])
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro');
    }

    public function salvarIndicacaoNomeInvalidoTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'nome'              => $this->nome(),
                'email'             => $this->email(),
                'telefone'          => $this->telefone(),
                'mensagem'          => 'Mensagem de teste',
                'status'            => valorAleatorio($this->statusValidos)
            ])
            ->post('/parceiro-indicacao')
            ->array();

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro');
    }

    public function listarIndicacoesNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:listar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->parametro([
                'pagina' => 1
            ])
            ->get('/parceiro-indicacao');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function buscarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:buscar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function editarStatusIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:atualizarStatus');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'status' => valorAleatorio($this->statusValidos)
            ])
            ->put('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }

    public function deletarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:deletar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->delete('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }
}
