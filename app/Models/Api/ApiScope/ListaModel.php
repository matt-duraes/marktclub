<?php

namespace App\Models\Api\ApiScope;

use ORM\ORM;

final class ListaModel extends ORM
{
    protected string $ormTabela = TABELA_AUTH_SCOPE_GRUPO;

    public function listar()
    {
    }
}
