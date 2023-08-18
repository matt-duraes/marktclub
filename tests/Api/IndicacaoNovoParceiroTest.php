<?php

namespace Tests\Api;

use Tests\Api\Token\Clube;

class IndicacaoNovoParceiroTest extends Clube
{
    private array $statusValidos = ['pendente', 'visualizado'];
    private string $idIndicacaoNovoParceiro;

    public function salvarIndicacaoNovoParceiroTest(): IndicacaoNovoParceiroTest
    {
        $this->api('indicacao_novo_parceiro:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'nome_indicado'     => $this->nomeCompleto(),
                'email_indicado'    => $this->email(),
                'telefone_indicado' => $this->telefone(),
                'mensagem'          => 'Mensagem de teste',
                'status'            => valorAleatorio($this->statusValidos)
            ])
            ->post('/indicacao-novo-parceiro')
            ->array();

        $this->idIndicacaoNovoParceiro = $dado['dado']['id'];

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarIndicacoesNovoParceiroTest(): IndicacaoNovoParceiroTest
    {
        $this->api('indicacao_novo_parceiro:listar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->parametro([
                'pagina' => 1
            ])
            ->get('/indicacao-novo-parceiro');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function buscarIndicacaoNovoParceiroTest(): IndicacaoNovoParceiroTest
    {
        $this->api('indicacao_novo_parceiro:buscar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/indicacao-novo-parceiro/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function editarStatusIndicacaoNovoParceiroTest(): IndicacaoNovoParceiroTest
    {
        $this->api('indicacao_novo_parceiro:atualizarStatus');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'status' => valorAleatorio($this->statusValidos)
            ])
            ->put('/indicacao-novo-parceiro/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }

    public function deletarIndicacaoNovoParceiroTest(): IndicacaoNovoParceiroTest
    {
        $this->api('indicacao_novo_parceiro:deletar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->delete('/indicacao-novo-parceiro/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }
}
