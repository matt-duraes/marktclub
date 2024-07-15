<?php

namespace Painel\DemandaSprint\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $vazio = empty($filtro) && empty($pesquisa);
        if ($vazio && (!array_key_exists('status', $filtro) || empty($filtro['status']))) {
            $filtro['publicado'] = 'sim';
        }
        return $filtro;
    }
}
