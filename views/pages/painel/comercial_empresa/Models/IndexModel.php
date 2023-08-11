<?php

namespace Painel\ComercialEmpresa\Models;

use App\Classes\ComercialEmpresa\Status;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro): array
    {
        if (!array_key_exists('status', $filtro)) {
            $filtro['status'] = Status::ATIVO;
        }
        return $filtro;
    }
}
