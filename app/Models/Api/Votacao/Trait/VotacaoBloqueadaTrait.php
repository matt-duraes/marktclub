<?php

namespace App\Models\Api\Votacao\Trait;

use Helpers\OrmHelper;

trait VotacaoBloqueadaTrait
{
    private function votacaoBloqueada(int $id): bool
    {
        $votacao = (new OrmHelper(TABELA_VOTACAO_DADO))->pegarCampoPor(
            campo: 'bloqueado',
            where: ['id', $id]
        );
        return $votacao == 1;
    }
}
