<?php

namespace App\Models\Api\AdminEmpresa;

use ORM\ORM;
use stdClass;

final class EmpresaModel extends ORM
{
    protected string $_tabela = TABELA_EMPRESA_NOVO;

    public function listaIdEmpresasValidas(): array
    {
        $dado = $this
            ->campo(['id'])
            ->where(['status', 'in', [1, 2]])
            ->read();

        $id = [];
        foreach ($dado as $r) {
            $id[] = $r->id;
        }
        return $id;
    }
}
