<?php

namespace Painel\ParceiroExterno\Models;

use stdClass;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements
    PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $slug = sessao('EMPRESA')['slug'] ?? '';
        $reg = '/ \- ' . $slug . '$/';
        $dado->titulo_interno = preg_replace($reg, '', $dado->titulo_interno);
        return $dado;
    }
}
