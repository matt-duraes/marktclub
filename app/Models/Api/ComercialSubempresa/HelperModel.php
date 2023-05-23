<?php

namespace App\Models\Api\ComercialSubempresa;

use ORM\ORM;

final class HelperModel extends ORM
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    public function pegarIdPeloUuid(string $uuid, int $empresa)
    {
        return $this->campo(['id'])->where([
            ['cod', $uuid],
            ['id_admin_empresa', $empresa]
        ])->primeiro(campo: 'id', padrao: 0);
    }
}
