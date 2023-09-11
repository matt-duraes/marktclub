<?php

namespace App\Models\Api\UsuarioCliente\Senha;

use ORM\ORM;
use Modules\Senha;

final class AlterarSenhaModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public array $token;

    public function __construct(
        private Senha $senha,
        private string $usuario,
        private string $hash,
    ) {
        parent::__construct();
    }
}
