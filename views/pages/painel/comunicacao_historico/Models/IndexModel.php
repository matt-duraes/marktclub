<?php

namespace Painel\ComunicacaoHistorico\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro): array
    {
        if (!array_key_exists('publicado', $filtro)) {
            $filtro['publicado'] = 'sim';
        } elseif (array_key_exists('publicado', $filtro) && $filtro['publicado'] == 'todos') {
            unset($filtro['publicado']);
        }
        return $filtro;
    }
}
