<?php

namespace App\Models\Api\Parceiro\Loja;

use Helpers\OrmHelper;

final class LojaHelper extends OrmHelper
{
    public function __construct()
    {
        parent::__construct(TABELA_PARCEIRO_LOJA);
    }
}
