<?php

namespace Tests\Api;

use Tests\Token\Clube;

class SolicitacaoContatoTest extends Clube
{
    private string $id;

    public function __construct()
    {
        parent::__construct();
    }

    public function listarSolicitacaoTest()
    {
        $this->pegarToken();
        $this->api('solicitacao_contato:listar');
        $this
            ->Curl
            ->json(['pagina' => 1])
            ->get('/solicitacao-contato');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarSolicitacaoTest()
    {
        $this->pegarToken();
        $this->api('solicitacao_contato:salvar');
        $dado = $this
            ->Curl
            ->body([
                'nome'     => nomeCompletoAleatorio(),
                'email'    => emailAleatorio(),
                'telefone' => telefoneAleatorio(),
                'mensagem' => 'Mensagem de teste',
                'url'      => 'https://www.google.com.br'
            ])
            ->post('/solicitacao-contato')
            ->array();

        $this->id = $dado['dado']['id'];

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.empresa.nome', 'Markt Club');
    }

    public function buscarSolicitacaoTest()
    {
        $this->pegarToken();
        $this->api('solicitacao_contato:buscar');
        $this
            ->Curl
            ->get('/solicitacao-contato/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function salvarSolicitacaoUrlAnafeTest()
    {
        $this->pegarToken();
        $this->api('solicitacao_contato:salvar');
        $this
            ->Curl
            ->body([
                'nome'     => nomeCompletoAleatorio(),
                'email'    => emailAleatorio(),
                'telefone' => telefoneAleatorio(),
                'mensagem' => 'Mensagem de teste',
                'url'      => 'anafecard.com.br'
            ])
            ->post('/solicitacao-contato');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.empresa.nome', 'Anafe Card');
    }

    public function salvarSolicitacaoSemTokenTest()
    {
        $this->api('solicitacao_contato:salvar');
        $this->removerToken();
        $this
            ->Curl
            ->body([
                'nome'     => nomeCompletoAleatorio(),
                'email'    => emailAleatorio(),
                'telefone' => telefoneAleatorio(),
                'mensagem' => 'Mensagem de teste',
                'url'      => ''
            ])
            ->post('/solicitacao-contato');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    public function atualizarStatusSolicitacaoTest()
    {
        $this->api('solicitacao_contato:atualizar');
        $this->pegarToken();

        $this
            ->Curl
            ->body([
                'status' => '2'
            ])
            ->put('/solicitacao-contato/' . $this->id);

        return $this
            ->checkStatus(204);
    }
}
