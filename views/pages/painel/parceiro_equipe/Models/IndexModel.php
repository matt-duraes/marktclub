<?php

namespace Painel\ParceiroEquipe\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements
    PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        return [
            'equipe' => 'sem-equipe'
        ];
    }
}
