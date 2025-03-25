<?php

namespace Painel\ComercialResponsavel\Models;

use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\Status;
use Modules\Botao;
use System\Interface\PainelIndexFiltroInterface;

final class IndexModel implements PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['sem_responsavel'] = Botao::SIM;
        $filtro['prospeccao_status'] = ProspeccaoStatus::PESQUISA;
        $filtro['status'] = Status::PROSPECCAO;
        return $filtro;
    }
}
