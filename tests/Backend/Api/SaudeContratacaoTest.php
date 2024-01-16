<?php

namespace Tests\Api;

use App\Classes\Saude\Status;
use Erro\Excecao;
use Tests\Token\Clube;

final class SaudeContratacaoTest extends Clube
{
    private string $idSimulacao = '9d732e45-33ce-4e4d-a736-423cb057bd5e';
    private string $idContratacao;
    private string $status;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();

        $this->status = valorAleatorio(array_keys((new Status())->select()));
    }

    /**
     * @return SaudeContratacaoTest
     * @throws Excecao
     */
    public function realizarContratacaoTest(): SaudeContratacaoTest
    {
        $dado = $this->Curl
            ->body($this->pegarDadosFicticios())
            ->post('/saude-contratacao')
            ->array();

        $this->idContratacao = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    public function validarSeStatusEstaNovoTest(): SaudeContratacaoTest
    {
        $this->Curl
            ->get("/saude-contratacao/{$this->idContratacao}")
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.status', 'novo');
    }

    public function naoRealizarContratacaoSemSimulacaoTest(): SaudeContratacaoTest
    {
        $body = $this->pegarDadosFicticios();
        unset($body['id_saude_simulacao']);

        $this->Curl
            ->body($body)
            ->post('/saude-contratacao')
            ->array();

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'id_saude_simulacao'.");
    }

    public function naoRealizarContratacaoComSimulacaoInvalidaTest(): SaudeContratacaoTest
    {
        $body = $this->pegarDadosFicticios();
        $body['id_saude_simulacao'] = 'SIMULACAO INVALIDA';

        $this->Curl
            ->body($body)
            ->post('/saude-contratacao')
            ->array();

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar um COD ou UUID para fazer a busca.');
    }

    public function buscarDadosContratacaoTest(): SaudeContratacaoTest
    {
        $this->Curl
            ->get("/saude-contratacao/{$this->idContratacao}")
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    public function listarContratacaoTest(): SaudeContratacaoTest
    {
        $this->Curl
            ->json([
                'pagina' => 1,
            ])
            ->get('/saude-contratacao')
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceExiste('dado.lista.0.id');
    }

    public function atualizarStatusTest(): SaudeContratacaoTest
    {
        $this->Curl
            ->body([
                'status' => $this->status,
            ])
            ->put("/saude-contratacao/{$this->idContratacao}")
            ->array();

        return $this
            ->checkStatus(204);
    }

    public function validarSeStatusMudouTest(): SaudeContratacaoTest
    {
        $this->Curl
            ->get("/saude-contratacao/{$this->idContratacao}")
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.status', $this->status);
    }

    /**
     * @return array
     */
    private function pegarDadosFicticios(): array
    {
        return [
            'id_saude_simulacao'          => $this->idSimulacao,
            'documento_cpf'               => $this->cpf(),
            'documento_rg'                => $this->rg(),
            'orgao_expedidor'             => 'ssp',
            'nome'                        => $this->nomeCompleto(),
            'data_nascimento'             => $this->dataPassada(),
            'estado_civil'                => $this->estadoCivil(),
            'naturalidade'                => 'brasileiro',
            'genero'                      => $this->genero(),
            'peso'                        => 75.4,
            'altura'                      => 1.80,
            'nome_mae'                    => $this->nomeCompleto(),
            'responsavel_cpf'             => $this->cpf(),
            'responsavel_rg'              => $this->rg(),
            'responsavel_orgao_expedidor' => 'ssp',
            'responsavel_nome'            => $this->nomeCompleto(),
            'email_pessoal'               => $this->email(),
            'telefone_celular'            => $this->telefoneCelular(),
            'telefone_residencial'        => $this->telefoneFixo(),
            'telefone_comercial'          => $this->telefoneFixo(),
            'telefone_comercial_ramal'    => '1',
            'endereco_logradouro'         => 'Rua tal',
            'endereco_cep'                => $this->cep(),
            'endereco_estado'             => $this->estado(),
            'endereco_cidade'             => $this->cidade($this->estado()),
            'endereco_bairro'             => $this->bairro(),
            'endereco_numero'             => 1,
            'endereco_complemento'        => ''
        ];
    }
}
