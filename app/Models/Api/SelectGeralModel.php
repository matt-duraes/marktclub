<?php

namespace App\Models\Api;

use ORM\ORM;

final class SelectGeralModel extends ORM
{
    public function __construct(
        string $tabela
    ) {
        $this->ormTabela = $tabela;
        parent::__construct();
    }
}
