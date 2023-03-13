<?php

namespace App\Models\Api\SolicitacaoSalavip\Trait;

use App\Classes\SolicitacaoSalavip\Empresa;

trait WhereTrait
{
    private function pegarWhere(): array
    {
        $where = [
            ['status', 2],
            ['data_validacao', '>=', '2018-06-01'],
            ['vinculo', 'in', ['2', '8074', '890713a200a9e45aa85e2ae67aa41e74', '9792e058562303f9e7e0604c5117c569']],
        ];

        $Empresa = new Empresa($this->request->empresa);
        if (!$Empresa->vazio() && $Empresa->valido()) {
            $where[] = ['empresa', $Empresa->numero()];
        } else {
            $where[] = ['empresa', 'in', [2, 66]];
        }

        $dataDe = $this->request->data_de;
        if (validarDataDate($dataDe)) {
            $where[] = ['data_validacao', '>=', dataBanco($dataDe)];
        }
        $dataAte = $this->request->data_ate;
        if (validarDataDate($dataAte)) {
            $where[] = ['data_validacao', '<=', dataBanco($dataAte) . ' 23:59:59'];
        }
        return $where;
    }
}
