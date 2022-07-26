<?php

namespace App\Models\Api\AdminEmpresa;

use ORM\ORM;
use stdClass;

final class EmpresaModel extends ORM
{
    protected string $_tabela = TABELA_EMPRESA_NOVO;

    public function pegarSelect(): array
    {
        $dado = $this
            ->campo(['cod', 'nome_fantasia'])
            ->where(['status', 'in', [1, 2]])
            ->order('nome_fantasia', 'ASC')
            ->read();

        return montarSelect($dado, indice: 'cod', valor: 'nome_fantasia');
    }
}
