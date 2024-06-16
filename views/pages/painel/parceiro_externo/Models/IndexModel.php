<?php

namespace Painel\ParceiroExterno\Models;

use stdClass;
use App\Classes\ParceiroLoja\Status;
use System\Interface\PainelIndexFiltroInterface;
use System\Interface\PainelIndexRetornoInterface;

final class IndexModel implements
    PainelIndexFiltroInterface,
    PainelIndexRetornoInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        if (!temPermissao('parceiro_externo_equipe')) {
            $filtro['equipe'] = USUARIO_ID;
        }
        if (!array_key_exists('status', $filtro)) {
            $filtro['status'] = (new Status(Status::PROSPECCAO))->indice();
        }
        if (empty($ordem)) {
            $filtro['ordem'] = 'status';
        }
        return $filtro;
    }

    public function retorno(stdClass $dado): stdClass
    {
        $retorno = [];
        $slug = sessao('EMPRESA')['slug'] ?? '';
        $reg = '/ \- ' . $slug . '$/';
        foreach ($dado->dado->lista as $r) {
            $r->titulo_interno = preg_replace($reg, '', $r->titulo_interno);
            $retorno[] = $r;
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }
}
