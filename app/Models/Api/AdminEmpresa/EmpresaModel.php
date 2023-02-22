<?php

namespace App\Models\Api\AdminEmpresa;

use ORM\ORM;
use stdClass;
use App\Classes\AdminEmpresa\Helper;

final class EmpresaModel extends ORM
{
    protected string $_tabela = TABELA_EMPRESA_NOVO;

    public function listaIdEmpresasValidas(): array
    {
        $dado = $this
            ->campo(['id'])
            ->where(['status', 'in', Helper::STATUS_LIBERADO])
            ->read();

        $id = [];
        foreach ($dado as $r) {
            $id[] = $r->id;
        }
        return $id;
    }
}
