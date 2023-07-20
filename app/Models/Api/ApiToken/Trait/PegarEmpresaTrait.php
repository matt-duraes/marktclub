<?php

namespace App\Models\Api\ApiToken\Trait;

use Helpers\OrmHelper;

trait PegarEmpresaTrait
{
    private function pegarEmpresa($where)
    {
        $Empresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $Empresa = $Empresa->pegarUltimoRegistro(
            where: $where,
            campo: ['id', 'uuid', 'slug'],
            retorno: 'object'
        );
        return $Empresa;
    }
}
