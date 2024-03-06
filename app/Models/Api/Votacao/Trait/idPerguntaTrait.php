<?php

namespace App\Models\Api\Votacao\Trait;

use Helpers\OrmHelper;

trait idPerguntaTrait
{
    private function idPergunta(string $uuid): int
    {
        return (new OrmHelper(TABELA_VOTACAO_PERGUNTA))->pegarIdPeloUuid($uuid);
    }
}
