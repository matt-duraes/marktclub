<?php

namespace App\Models\Api\Pagina;

interface PaginaInterface
{
    /**
     * Pegar o html da págin
     */
    public function pegarHtml(): array;
}
