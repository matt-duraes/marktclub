<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Token\Clube;

final class SaudeContratacaoTest extends Clube
{
    private string $idSimulacao = '9d732e45-33ce-4e4d-a736-423cb057bd5e';

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    /**
     * @return SaudeContratacaoTest
     * @throws Excecao
     */
    public function realizarContratacaoTest(): SaudeContratacaoTest
    {
        $this->Curl
            ->body($this->pegarDadosFicticios())
            ->post('/saude/contratacao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
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
