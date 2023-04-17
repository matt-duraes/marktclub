<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;

final class HelperModel extends ORM
{
    protected string $ormTabela = TABELA_ANALYTICS;

    public function pegarUltimoRegistroPeloUsuario(int $id, array $campo = null): array
    {
        $campo = empty($campo) ? ['*'] : $campo;
        return $this->campo($campo)->where(['usuario', $id])->order('id', 'DESC')->primeiro(retorno: 'array');
    }
}
