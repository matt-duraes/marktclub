<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Api\Token\Clube;

final class SaudeContratacaoTest extends Clube
{
    private string $idSimulacao = '32dd2783-daf2-4cf4-be78-6f43e3f801c5';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return SaudeContratacaoTest
     * @throws Excecao
     */
    public function realizarContratacaoTest(): SaudeContratacaoTest
    {
        $this->api('saude_contratacao:salvar');
        $this->Curl
            ->header(['Authorization' => $this->pegarToken()])
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
            'id_simulacao'         => $this->idSimulacao,
            'documento_cpf'        => $this->cpf(),
            'documento_rg'         => $this->rg(),
            'orgao_expedidor'      => 'ssp',
            'nome'                 => $this->nomeCompleto(),
            'data_nascimento'      => $this->dataPassada(),
            'estado_civil'         => $this->estadoCivil(),
            'naturalidade'         => 'brasileiro',
            'sexo'                 => $this->genero(),
            'peso'                 => 75.4,
            'altura'               => 1.80,
            'filiacao'             => '',
            'cpf_responsavel'      => '',
            'rg_responsavel'       => '',
            'nome_responsavel'     => '',
            'email'                => $this->email(),
            'telefone_celular'     => $this->telefoneCelular(),
            'telefone_residencial' => $this->telefoneFixo(),
            'telefone_comercial'   => $this->telefoneFixo(),
            'ramal'                => '',
            'endereco'             => 'Rua tal',
            'cep'                  => $this->cep(),
            'estado'               => $this->estado(),
            'cidade'               => $this->cidade($this->estado()),
            'bairro'               => $this->bairro(),
            'numero'               => 1,
            'complemento'          => '',
            'status'               => 1
        ];
    }
}
