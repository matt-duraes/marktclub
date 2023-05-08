<?php

namespace System\Interface;

use stdClass;

interface PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass;
}
