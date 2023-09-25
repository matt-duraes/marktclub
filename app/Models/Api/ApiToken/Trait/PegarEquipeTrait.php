<?php

namespace App\Models\Api\ApiToken\Trait;

use Helpers\OrmHelper;

trait PegarEquipeTrait
{
    private function pegarEquipe($where)
    {
        $Usuario = new OrmHelper(TABELA_USUARIO_EQUIPE);
        $Usuario = $Usuario->pegarPrimeiroRegistro(
            where: $where,
            campo: ['id', 'uuid', 'id_admin_empresa', 'id_admin_subempresa', 'permissao'],
            retorno: 'object'
        );
        $Usuario->permissao = jsonDecode($Usuario->permissao, true, true);
        return $Usuario;
    }
}
