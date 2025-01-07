<?php

namespace Painel\IndiqueConcorra\Models;

use stdClass;
use App\Classes\ComercialEmpresa\Status;
use System\Interface\PainelIndexFiltroInterface;
use System\Interface\PainelIndexRetornoInterface;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;

final class IndexModel implements
    PainelIndexFiltroInterface,
    PainelIndexRetornoInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        return [
            'dono' => sessao('USUARIO.id'),
        ];
    }

    public function retorno(stdClass $dado): stdClass
    {
        $lista = $dado->dado->lista ?? [];
        $retorno = [];
        $Status = new Status();
        $ProspeccaoStatus = new ProspeccaoStatus();
        foreach ($lista as $r) {
            $status = $r->status;
            if ($status == $Status::SEM_RESULTADO) {
                $r->prospeccao_status = $ProspeccaoStatus::SEM_RESULTADO;
            } elseif ($status == $Status::ATIVO) {
                $r->prospeccao_status = $ProspeccaoStatus::CONCLUIDO;
            }
            $retorno[] = $r;
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }
}
