<?php

namespace App\Models\Api\Votacao\Trait;

use Helpers\OrmHelper;

trait idVotacaoTrait
{
    private function idVotacao(string $uuid): int
    {
        return (new OrmHelper(TABELA_VOTACAO_DADO))->pegarIdPeloUuid($uuid);
    }
}
