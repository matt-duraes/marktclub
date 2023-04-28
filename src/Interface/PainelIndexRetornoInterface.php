<?php

namespace System\Interface;

use stdClass;

interface PainelIndexRetornoInterface
{
    public function retorno(stdClass $dado): stdClass;
}
