<?php

namespace Painel\Votacao\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['tipo'] = 'votacao';
        return $filtro;
    }
}
