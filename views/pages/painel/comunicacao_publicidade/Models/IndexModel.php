<?php

namespace Painel\ComunicacaoPublicidade\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, bool $pesquisa): array
    {
        if (!array_key_exists('publicado', $filtro)) {
            $filtro['publicado'] = 'sim';
        } elseif (array_key_exists('publicado', $filtro) && $filtro['publicado'] == 'todos') {
            unset($filtro['publicado']);
        }
        return $filtro;
    }
}
