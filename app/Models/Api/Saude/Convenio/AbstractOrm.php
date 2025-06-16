<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;

abstract class AbstractOrm extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;
}
