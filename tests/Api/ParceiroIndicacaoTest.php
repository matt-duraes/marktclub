<?php

namespace Tests\Api;

use App\Classes\ParceiroIndicacao\Status;
use Erro\Excecao;
use Tests\Api\Token\Clube;

class ParceiroIndicacaoTest extends Clube
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
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function salvarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/parceiro-indicacao')
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
            'mensagem' => 'Mensagem de teste ' . $this->numero()
        ];
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function naoPodeSalvarSemNomeTest(): ParceiroIndicacaoTest
    {
        $body = $this->getBody();
        unset($body['nome']);

        $this
            ->Curl
            ->body($body)
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'nome'.")
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function naoPodeSalvarSemEmailTest(): ParceiroIndicacaoTest
    {
        $body = $this->getBody();
        unset($body['email']);

        $this
            ->Curl
            ->body($body)
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'email'.")
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function naoPodeEnviarSemTelefoneTest(): ParceiroIndicacaoTest
    {
        $body = $this->getBody();
        unset($body['telefone']);

        $this
            ->Curl
            ->body($body)
            ->post('/parceiro-indicacao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'telefone'.")
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function listarIndicacoesNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this
            ->Curl
            ->json([
                'pagina'     => 1,
                'quantidade' => '',
                'ordem'      => '',
                'status'     => $this->random($this->statusValidos)
            ])
            ->get('/parceiro-indicacao');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function buscarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this
            ->Curl
            ->get('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idIndicacaoNovoParceiro);
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function editarStatusIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this
            ->Curl
            ->body([
                'status' => valorAleatorio($this->statusValidos)
            ])
            ->put('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function naoPodeEditarStatusInvalidoTest(): ParceiroIndicacaoTest
    {
        $this
            ->Curl
            ->body([
                'status' => 'STATUS INVALIDO'
            ])
            ->put('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.')
            ->checkIndiceIgual('status', 'erro');
    }

    /**
     * @return ParceiroIndicacaoTest
     * @throws Excecao
     */
    public function deletarIndicacaoNovoParceiroTest(): ParceiroIndicacaoTest
    {
        $this
            ->Curl
            ->delete('/parceiro-indicacao/' . $this->idIndicacaoNovoParceiro);

        return $this
            ->checkStatus(204);
    }
}
