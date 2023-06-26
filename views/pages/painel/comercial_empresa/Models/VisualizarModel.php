<?php

namespace Painel\ComercialEmpresa\Models;

use stdClass;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $dado->valor_usuario = '';
        $dado->valor_cobranca = '';
        return $dado;
    }
}
