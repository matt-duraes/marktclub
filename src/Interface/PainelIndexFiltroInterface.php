<?php

namespace System\Interface;

interface PainelIndexFiltroInterface
{
    public function filtro(array $filtro, bool $pesquisa): array;
}
