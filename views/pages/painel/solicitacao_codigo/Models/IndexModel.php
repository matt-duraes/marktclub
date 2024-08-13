<?php

namespace Painel\SolicitacaoCodigo\Models;

use App\Classes\SolicitacaoCodigo\Status;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['status'] = Status::ABERTO;
        return $filtro;
    }
}
