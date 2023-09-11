<?php

namespace App\Models\Api\UsuarioCliente\Senha;

use ORM\ORM;
use Modules\Inteiro;

final class ValidarCodigoModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public string $hash;

    public function __construct(
        private string $usuario,
        private Inteiro $codigo,
    ) {
        parent::__construct();
    }
}
