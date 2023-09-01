<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Api\Token\Clube;
use App\Classes\SolicitacaoLoja\Status;

class SolicitacaoLojaTest extends Clube
{
    private array $statusValidos;
    private string $idIndicacaoNovoParceiro;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->statusValidos = array_keys((new Status())->select());
        $this->pegarToken();
        parent::__construct();
    }

    /**
     * @return SolicitacaoLojaTest
     * @throws Excecao
     */
    public function salvarIndicacaoNovoParceiroTest(): SolicitacaoLojaTest
    {
        $dado = $this
            ->Curl
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
            'nome'     => $this->nomeCompleto(),
            'email'    => $this->email(),
            'telefone' => $this->telefone(),
            'mensagem' => 'Mensagem de teste ' . $this->numero(),
            'origem'   => 'clube'
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

        $this
            ->Curl
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

        $this
            ->Curl
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

        $this
            ->Curl
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
        $this
            ->Curl
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
        $this
            ->Curl
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
        $this
            ->Curl
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
        $this
            ->Curl
            ->delete('/solicitacao-loja/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }
}
