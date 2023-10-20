<?php

namespace Tests\Api;

use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use Erro\Excecao;
use Tests\Token\Clube;

class SolicitacaoLojaTest extends Clube
{
    private array $statusValidos;
    private string $idIndicacaoNovoParceiro;

    public function __construct()
    {
        $this->statusValidos = array_keys((new Status())->select());
        parent::__construct();
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function salvarIndicacaoNovoParceiroTest(): SolicitacaoLojaTest
    {
        $this->api('solicitacao_loja:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->post('/solicitacao-loja')
            ->array();

        $this->idIndicacaoNovoParceiro = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('status', 'sucesso');
    }

    /**
     * @return array
     */
    private function getBody(): array
    {
        return [
            'nome'     => nomeCompletoAleatorio(),
            'email'    => emailAleatorio(),
            'telefone' => telefoneAleatorio(),
            'mensagem' => 'Mensagem de teste ' . numeroAleatorio(),
            'origem'   => Origem::CLUBE
        ];
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function naoPodeSalvarSemNomeTest(): SolicitacaoLojaTest
    {
        $body = $this->getBody();
        unset($body['nome']);

        $this->api('solicitacao_loja:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/solicitacao-loja');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'nome'.")
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function naoPodeSalvarSemEmailTest(): SolicitacaoLojaTest
    {
        $body = $this->getBody();
        unset($body['email']);

        $this->api('solicitacao_loja:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/solicitacao-loja');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'email'.")
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function naoPodeEnviarSemTelefoneTest(): SolicitacaoLojaTest
    {
        $body = $this->getBody();
        unset($body['telefone']);

        $this->api('solicitacao_loja:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/solicitacao-loja');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'telefone'.")
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function listarIndicacoesNovoParceiroTest(): SolicitacaoLojaTest
    {
        $this->api('solicitacao_loja:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'     => 1,
                'quantidade' => '',
                'ordem'      => '',
                'status'     => $this->random($this->statusValidos)
            ])
            ->get('/solicitacao-loja');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function buscarIndicacaoNovoParceiroTest(): SolicitacaoLojaTest
    {
        $this->api('solicitacao_loja:buscar');
        $this
            ->Curl
            ->get('/solicitacao-loja/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idIndicacaoNovoParceiro);
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function editarStatusIndicacaoNovoParceiroTest(): SolicitacaoLojaTest
    {
        $this->api('solicitacao_loja:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => valorAleatorio($this->statusValidos)
            ])
            ->put('/solicitacao-loja/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function naoPodeEditarStatusInvalidoTest(): SolicitacaoLojaTest
    {
        $this->api('solicitacao_loja:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'STATUS INVALIDO'
            ])
            ->put('/solicitacao-loja/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.')
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function deletarIndicacaoNovoParceiroTest(): SolicitacaoLojaTest
    {
        $this->api('solicitacao_loja:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/solicitacao-loja/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }
}
