<?php

namespace Painel\ParceiroClube\Models;

use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use System\Interface\PainelIndexFiltroInterface;

class IndexModel implements
    PainelIndexFiltroInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $filtro['empresas'] = [sessao('EMPRESA')['id']];
        if (empty($filtro['tipo_loja'])) {
            $filtro['tipo_loja'] = TipoLoja::LOJA;
        }
        if (empty($filtro['status'])) {
            $filtro['status'] = Status::CONCLUIDO;
        }
        if (empty($ordem)) {
            $filtro['ordem'] = Ordem::TITULO_AZ;
        }
        return $filtro;
    }
}
