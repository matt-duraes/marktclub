<?php

namespace Painel\SiliumDeposito\Models;

use App\Classes\SiliumDeposito\TipoOperacao;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['tipo_operacao'] = TipoOperacao::DEPOSITO;
        return $filtro;
    }
}
