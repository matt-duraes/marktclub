<?php

namespace Painel\ComercialResponsavel\Models;

use Modules\Botao;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['sem_responsavel'] = Botao::SIM;
        return $filtro;
    }
}
