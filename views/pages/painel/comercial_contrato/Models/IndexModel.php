<?php

namespace Painel\ComercialContrato\Models;

use App\Classes\ComercialEmpresa\Status;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['status'] = Status::PROSPECCAO;
        return $filtro;
    }
}
