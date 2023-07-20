<?php

namespace App\Models\Api\ApiToken\Trait;

use Helpers\OrmHelper;

trait PegarClienteTrait
{
    private function pegarCliente($where)
    {
        $Usuario = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $Usuario = $Usuario->pegarPrimeiroRegistro(
            where: $where,
            campo: ['id', 'uuid', 'id_admin_empresa'],
            retorno: 'object'
        );
        return $Usuario;
    }
}
