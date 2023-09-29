<?php

namespace Painel\ComercialPerdido\Models;

use App\Classes\ComercialEmpresa\Status;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro): array
    {
        $filtro['status'] = Status::INATIVO;
        return $filtro;
    }
}
