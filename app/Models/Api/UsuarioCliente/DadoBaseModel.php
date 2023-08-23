<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use Modules\Nome;
use Modules\Email;

final class DadoBaseModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public Nome $nome;
    public Email $emailPessoal;
    public Email $emailTrabalho;
    public Email $email;
    public string $imagem;
    public string $id;
    public bool $existe = false;

    public function __construct(
        int|string $usuario
    ) {
        parent::__construct();
        $this->setarUsuario();

        if (empty($usuario)) {
            return;
        }

        $this->buscarUsuario($usuario);
    }

    private function buscarUsuario($id)
    {
        $usuario = $this
            ->campo(['uuid', 'nome', 'email_pessoal', 'email_trabalho', 'imagem'])
            ->where($this->pegarWhere($id))
            ->primeiro();

        if (!$usuario) {
            return;
        }

        $this->existe = true;
        $this->setarUsuario(
            id: $usuario->uuid,
            nome: $usuario->nome,
            emailPessoal: $usuario->email_pessoal,
            emailTrabalho: $usuario->email_trabalho,
            imagem: !empty($usuario->imagem) ? $usuario->imagem : imagemUsuario(),
        );
    }

    private function setarUsuario(
        string $id = '',
        string $nome = '',
        string $emailPessoal = null,
        string $emailTrabalho = null,
        string $imagem = ''
    ) {
        $this->id = $id;
        $this->imagem = $imagem;
        $this->nome = new Nome($nome);
        $this->email = new Email(!empty($emailPessoal) ? $emailPessoal : $emailTrabalho);
        $this->emailPessoal = new Email($emailPessoal);
        $this->emailTrabalho = new Email($emailTrabalho);
    }

    private function pegarWhere($id)
    {
        if (is_int($id)) {
            return ['id', $id];
        }
        return ['uuid', $id];
    }
}
