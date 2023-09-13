<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;

final class UsuarioLogadoModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    public function __construct(int $id)
    {
        parent::__construct();
        $this->usuarioLogou($id);
    }

    private function usuarioLogou($id)
    {
        $this
            ->dado([
                'hash'        => '',
                'hash_data'   => '',
                'hash_tipo'   => '',
                'data_acesso' => agora()
            ])
            ->where(['id', $id])
            ->update();
    }
}
