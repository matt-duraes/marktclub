<?php

namespace Painel\UsuarioGrupo\Models;

use App\Classes\UsuarioGrupo\Status;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        if (empty($filtro['status'])) {
            $filtro['status'] = Status::ATIVO;
        }
        return $filtro;
    }
}
