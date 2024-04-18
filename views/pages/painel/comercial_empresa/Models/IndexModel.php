<?php

namespace Painel\ComercialEmpresa\Models;

use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, bool $pesquisa): array
    {
        /*if (!array_key_exists('status', $filtro)) {
            $filtro['status'] = Status::ATIVO;
        }*/
        return $filtro;
    }
}
