<?php

namespace App\Models\Api\SolicitacaoPremium\Trait;

trait WhereTrait
{
    public function pegarWhere()
    {
        $where = [];
        if ($this->ormWherePadrao) {
            $where[] = $this->ormWherePadrao;
        }
        $where[] = ['data_criacao', 'between', [$this->de, $this->ate]];
        return $where;
    }
}
