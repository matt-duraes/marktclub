<?php

namespace App\Models\Api\Analytics\Trait;

trait MontarTrait
{
    private function montarRelatorioLista(array $where, array $dado)
    {
        $relatorio = [];
        $total = $this->contar($where);
        foreach ($dado as $r) {
            $relatorio[] = [
                'item' => !empty($r->item) ? $r->item : 'Outro',
                'total' => $r->quantidade,
                'porcentagem' => $r->quantidade == 0 ? 0 : number_format(($r->quantidade * 100) / $total, 2, '.')
            ];
        }
        return $relatorio;
    }
}
