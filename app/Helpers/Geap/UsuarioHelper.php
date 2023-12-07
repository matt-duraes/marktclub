<?php

namespace App\Helpers\Geap;

use Modules\Cpf;
use Modules\Nome;
use Modules\Email;
use Helpers\CurlHelper;

final class UsuarioHelper extends CurlHelper
{
    public Nome $nome;
    public Cpf $cpf;
    public Email $email;

    public function __construct(
        private readonly TokenHelper $token,
        private readonly string $login,
        private readonly string $senha
    ) {
        parent::__construct(env('GEAP_USARIO_LINK', ''));
        $this->cpf = new Cpf($login);
        $this->validarCpf();
        $this->buscarUsuario();
        $this->validarDado();
    }

    public function naoEncontrado()
    {
        mensagemErro('Erro!', 'O CPF e/ou Número do cartão estão inválidos, por favor, tente novamente.');
    }

    private function validarCpf()
    {
        if (!$this->cpf->valido()) {
            mensagemErro('Campo inválido!', 'O CPF informado não é um CPF válido.');
        }
    }

    private function buscarUsuario()
    {
        $usuario = $this
            ->header([
                'Authorization' => 'Bearer ' . $this->token->token
            ])
            ->parametro([
                'NroCpfCliente'    => $this->cpf->numero(),
                'NroCartaoCliente' => preg_replace('/[^0-9]/', '', $this->senha),
            ])
            ->get('/maisbeneficios/v1/MaisBeneficios/ConsultarStatusBeneficiario')
            ->object();

        if (!object_key_exists('isSuccess', $usuario) || true !== $usuario->isSuccess) {
            $this->naoEncontrado();
        }

        $this->nome = new Nome($usuario->resultData->nome ?? '');
        $this->email = new Email($usuario->resultData->email ?? '');
    }

    private function validarDado()
    {
        if ($this->nome->vazio() || !$this->nome->valido()) {
            mensagemErro('Nome inválido!', 'Nome inválido, atualize seu nome na GEAP para continuar.');
        } elseif ($this->email->vazio() || !$this->email->valido()) {
            mensagemErro('E-mail inválido!', 'E-mail inválido, atualize seu e-mail na GEAP para continuar.');
        }
    }
}
