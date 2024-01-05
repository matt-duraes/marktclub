<?php

namespace App\Models\Api\SiteConfig;

use ORM\ORM;

final class ConfigModel extends ORM
{
    protected string $ormTabela = TABELA_SITE_CONFIG;

    public function listarDados(): array
    {
        $dado = $this
            ->read();
        return $dado;
    }
}
