<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\ORM;
use App\Classes\ComercialEmpresa\Helper;

final class EmpresaValidaModel extends ORM
{
    protected string $_tabela = TABELA_COMERCIAL_EMPRESA;

    public function listarDados(): array
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
