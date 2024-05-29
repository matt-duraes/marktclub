<?php

namespace Painel\SiliumDeposito\Models;

use App\Classes\Silium\Tipo;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['tipo'] = Tipo::DEPOSITO;
        return $filtro;
    }
}
