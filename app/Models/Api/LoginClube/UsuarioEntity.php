<?php

namespace App\Models\Api\LoginClube;

use ORM\Entity;
use Modules\Cpf;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Senha;
use App\Classes\UsuarioCliente\Status;

final class UsuarioEntity extends Entity
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    protected array $_buscar = [
        'email' => ['email_pessoal', 'email_trabalho'],
        'senha' => 'salt',
        'nome', 'status', 'data_criacao', 'data_atualizacao', 'primeiro_acesso'
    ];

    public Status $status;
    public Senha $senha;
    public Email $email;
    public Nome $nome;
    public string $imagem;
    public Botao $primeiro_acesso;

    protected function regraPosBuscar()
    {
        $this->imagem = imagemUsuario();
    }
}
