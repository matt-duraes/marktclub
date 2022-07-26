<?php

namespace App\Models\Api\ApiUsuario;

use ORM\ORM;

final class UsuarioModel extends ORM
{
    protected string $_tabela = TABELA_AUTH_USUARIO;

    public function pegarSelect(): array
    {
        $dado = $this
            ->campo(['uuid', 'nome_usuario'])
            ->where(['status', 1])
            ->order('nome_usuario', 'ASC')
            ->read();

        return montarSelect($dado, indice: 'uuid', valor: 'nome_usuario');
    }
}
