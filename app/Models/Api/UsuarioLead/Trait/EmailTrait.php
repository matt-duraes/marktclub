<?php

namespace App\Models\Api\UsuarioLead\Trait;

use Modules\Email;
use Helpers\EmailHelper;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

trait EmailTrait
{
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Email $email_funcional;

    public function enviarEmailAprovado()
    {
        $email = $this->pegarEmail();
        if (empty($email)) {
            return;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['empresa' => $this->idEmpresa]);

        $titulo = $Construtor->titulo . ' - Bem vindo!';
        $nome = $this->nome->nome();
        $mensagem = '
            Para dar continuidade a seu cadastro, acesse nosso site pelo botão abaixo e clique em "Primeiro acesso":
        ';

        $Email = new EmailHelper();
        $Email->mensagem(
            $titulo,
            $titulo,
            $mensagem,
            posMensagem: '
                Informe seus dados para finalizar seu cadastro e criar sua senha. <br>
                Não perca tempo! Aproveite esta oportunidade e venha conhecer o maior clube de vantagens
                da América latina!',
            botaoTexto: 'ACESSAR SITE',
            botaoLink: $Construtor->link_clube,
            logo: $Construtor->link_logo,
            cor: $Construtor->cor
        );
        $Email->sendGrid($titulo, $nome, $email);
    }

    public function enviarEmailRecusado()
    {
        $email = $this->pegarEmail();
        if (empty($email)) {
            return;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->_id($this->idEmpresa);

        $titulo = $Construtor->titulo;
        $nome = $this->nome->nome();
        $mensagem = '
            Não foi possível continuar com seu cadastro no momento. Por favor, entre em contato para saber mais.
        ';

        $Email = new EmailHelper();
        $Email->mensagem(
            $titulo,
            $titulo,
            $mensagem,
            logo: $Construtor->link_logo,
            cor: $Construtor->cor
        );
        $Email->sendGrid($titulo, $nome, $email);
    }

    private function pegarEmail()
    {
        if ($this->email_pessoal->valido()) {
            return $this->email_pessoal->email();
        } else if ($this->email_trabalho->valido()) {
            return $this->email_trabalho->email();
        } else if ($this->email_funcional->valido()) {
            return $this->email_funcional->email();
        }
    }
}
