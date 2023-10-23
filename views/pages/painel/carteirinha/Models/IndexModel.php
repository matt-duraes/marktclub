<?php

namespace Painel\Carteirinha\Models;

use stdClass;
use PainelModel\Perfil\Empresa;
use System\Interface\PainelIndexRetornoInterface;

final class IndexModel implements PainelIndexRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $lista = $dado->dado->lista ?? [];
        $retorno = [];
        $Empresa = new Empresa();
        foreach ($lista as $r) {
            $r->empresa = $Empresa->unico($r->empresa);
            $retorno[] = $r;
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }
}
