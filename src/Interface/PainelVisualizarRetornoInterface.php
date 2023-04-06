<?php

namespace System\Interface;

use stdClass;

interface PainelVisualizarRetornoInterface
{
    public function __construct(stdClass $dado);
    public function retorno(): stdClass;
}
