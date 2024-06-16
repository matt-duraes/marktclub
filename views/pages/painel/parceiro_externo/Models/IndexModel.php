<?php

namespace Painel\ParceiroExterno\Models;

use stdClass;
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
        return $filtro;
    }

    public function retorno(stdClass $dado): stdClass
    {
        $retorno = [];
        foreach ($dado->dado->lista as $r) {
            $r->titulo_interno = preg_replace('/ \- [0-9]{1,5}$/', '', $r->titulo_interno);
            $retorno[] = $r;
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }
}
