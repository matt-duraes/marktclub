<?php

namespace System\Interface;

use stdClass;

interface PainelVisualizarBuscarInterface
{
    public function buscar(string $uuid): stdClass;
}
