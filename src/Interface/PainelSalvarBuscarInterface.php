<?php

namespace System\Interface;

use stdClass;

interface PainelSalvarBuscarInterface
{
    public function buscar(string $uuid): stdClass;
}
