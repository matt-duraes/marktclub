<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;

final class IdModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public function pegarIdPelaUrl(string $url): int
    {
        return $this->campo(['id'])->where(['url', $url])->primeiro(campo: 'id');
    }
}
