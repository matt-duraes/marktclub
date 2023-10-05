<?php

namespace App\Models\Api\UsuarioLead\Trait;

use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use Erro\Excecao;
use Helpers\EmailHelper;
use Modules\Email;
use SendGrid\Mail\TypeException;

trait EmailTrait
{
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Email $email_funcional;

    /**
     * @throws Excecao|TypeException
     */
    public function enviarEmailAprovado(): void
    {
        $email = $this->pegarEmail();
        if (empty($email)) {
            return;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['id_admin_empresa', $this->idEmpresa]);

        $titulo = $Construtor->titulo . ' - Bem vindo!';
        $nome = $this->nome->nome();
        $mensagem = '
            Para dar continuidade a seu cadastro, acesse nosso site pelo botão abaixo e clique em "Primeiro acesso":
        ';

        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: $titulo,
            mensagem: $mensagem,
            botaoTexto: 'ACESSAR SITE',
            botaoLink: $Construtor->link_clube,
            posMensagem: '
                Informe seus dados para finalizar seu cadastro e criar sua senha. <br>
                Não perca tempo! Aproveite esta oportunidade e venha conhecer o maior clube de vantagens
                da América latina!',
            logo: $Construtor->logo_principal,
            cor: $Construtor->cor_principal
        );
        $Email->sendGrid($titulo, $nome, $email);
    }

    /**
     * @return string|void
     */
    private function pegarEmail()
    {
        if ($this->email_pessoal->valido()) {
            return $this->email_pessoal->email();
        } elseif ($this->email_trabalho->valido()) {
            return $this->email_trabalho->email();
        } elseif ($this->email_funcional->valido()) {
            return $this->email_funcional->email();
        }
    }

    /**
     * @throws Excecao|TypeException
     */
    public function enviarEmailRecusado(): void
    {
        $email = $this->pegarEmail();
        if (empty($email)) {
            return;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['id_admin_empresa', $this->idEmpresa]);

        $titulo = $Construtor->titulo;
        $nome = $this->nome->nome();
        $mensagem = '
            Não foi possível continuar com seu cadastro no momento. Por favor, entre em contato para saber mais.
        ';

        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: $titulo,
            mensagem: $mensagem,
            logo: $Construtor->logo_principal,
            cor: $Construtor->cor_principal
        );
        $Email->sendGrid($titulo, $nome, $email);
    }
}
