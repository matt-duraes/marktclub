<?php

namespace Painel\ParceiroEasylive\Models;

use App\Classes\Geral\Status;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        if (!array_key_exists('status', $filtro) || empty($filtro['status'])) {
            $filtro['status'] = Status::ATIVO;
        }
        return $filtro;
    }
}
