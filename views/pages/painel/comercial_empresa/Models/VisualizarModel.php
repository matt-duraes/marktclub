<?php

namespace Painel\ComercialEmpresa\Models;

use stdClass;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements PainelVisualizarRetornoInterface
{
    public function __construct(
        private stdClass $dado
    ) {
    }

    public function retorno(): stdClass
    {
        return $this->dado;
    }
}
