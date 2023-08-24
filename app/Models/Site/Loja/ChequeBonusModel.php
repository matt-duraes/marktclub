<?php

namespace App\Models\Site\Loja;

use Http\Request;
use Modules\Botao;
use App\Helpers\ClubeApiHelper;

final class ChequeBonusModel extends ClubeApiHelper
{
    private array $body = [];

    public function __construct(private Request $request)
    {
        parent::__construct();
        $this->montarBody();
    }

    private function montarBody()
    {
        $request = $this->request;
        $this->body = [
            'tipo_usuario'               => $request->tipo_usuario,
            'nome'                       => $request->nome,
            'email_pessoal'              => $request->email_pessoal,
            'estado_civil'               => $request->estado_civil,
            'telefone_celular'           => $request->telefone_celular,
            'data_nascimento'            => $request->data_nascimento,
            'endereco_cep'               => $request->endereco_cep,
            'endereco_logradouro'        => $request->endereco_logradouro,
            'endereco_numero'            => $request->endereco_numero,
            'automovel'                  => $request->automovel,
            'endereco_complemento'       => $request->endereco_complemento,
            'endereco_bairro'            => $request->endereco_bairro,
            'endereco_estado'            => $request->endereco_estado,
            'endereco_cidade'            => $request->endereco_cidade,
            'rg'                         => $request->rg,
            'dependente_nome'            => $request->dependente_nome,
            'dependente_email_pessoal'   => $request->dependente_email,
            'dependente_cpf'             => $request->dependente_cpf,
            'dependente_rg'              => $request->dependente_rg,
            'dependente_grau_parentesco' => $request->dependente_grau_parentesco,
            'dependente_data_nascimento' => $request->dependente_data_nascimento,
            'data_termo'                 => hoje()
        ];
    }

    private function atualizarDado()
    {
        try {
            $request = $this->request;
            $this
                ->body([
                    'nome'                 => $request->nome,
                    'email_pessoal'        => $request->email_pessoal,
                    'estado_civil'         => $request->estado_civil,
                    'telefone_pessoal'     => $request->telefone_celular,
                    'data_nascimento'      => $request->data_nascimento,
                    'endereco_cep'         => $request->endereco_cep,
                    'endereco_logradouro'  => $request->endereco_logradouro,
                    'endereco_numero'      => $request->endereco_numero,
                    'endereco_complemento' => $request->endereco_complemento,
                    'endereco_bairro'      => $request->endereco_bairro,
                    'endereco_estado'      => $request->endereco_estado,
                    'endereco_cidade'      => $request->endereco_cidade,
                ])
                ->put('/usuario-cliente/' . sessao('USUARIO.id'));
        } catch (\Throwable $e) {
            if (eLocalhost()) {
                mensagemErro('Erro!', $e->getMessage());
            }
        }
    }

    public function salvar()
    {
        $this
            ->validar('Erro ao salvar solicitação, por favor, tente novamente.')
            ->body($this->body)
            ->post('/solicitacao-cheque-bonus');

        if ($this->request->atualizar == Botao::SIM) {
            $this->atualizarDado();
        }
    }
}
