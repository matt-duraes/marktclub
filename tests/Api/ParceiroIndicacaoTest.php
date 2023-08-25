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

    private function getBody()
    {
        return [
            'nome'              => $this->nomeCompleto(),
            'email'             => $this->email(),
            'telefone'          => $this->telefone(),
            'mensagem'          => 'Mensagem de teste',
        ];
    }

    public function salvarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($this->getBody())
            ->post('/parceiro-indicacao')
            ->array();

        $this->idIndicacaoNovoParceiro = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeSalvarSemNomeTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');

        $body = $this->getBody();
        unset($body['nome']);

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'nome'.")
            ->checkIndiceIgual('status', 'erro');
    }

    public function naoPodeSalvarSemEmailTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');

        $body = $this->getBody();
        unset($body['email']);

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'email'.")
            ->checkIndiceIgual('status', 'erro');
    }

    public function naoPodeEnviarSemTelefoneTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:salvar');

        $body = $this->getBody();
        unset($body['telefone']);

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'telefone'.")
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

    public function naoPodeEditarStatusInvalidoTest(): ParceiroIndicacaoTest
    {
        $this->api('parceiro_indicacao:atualizarStatus');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'status' => 'STATUS INVALIDO'
            ])
            ->put('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.')
            ->checkIndiceIgual('status', 'erro');
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
