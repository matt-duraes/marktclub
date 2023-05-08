<?php

namespace System\Interface;

use stdClass;

interface PainelIndexBuscarInterface
{
    public function buscar(
        ?int $pagina = null,
        ?string $pesquisa = null,
        ?array $filtro = [],
        string $ordem = null
    ): stdClass;
}
