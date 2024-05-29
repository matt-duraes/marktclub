<?php

namespace Painel\Enquete\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['tipo'] = 'enquete';
        return $filtro;
    }
}
