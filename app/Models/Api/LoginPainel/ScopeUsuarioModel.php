<?php

namespace App\Models\Api\LoginPainel;

final class ScopeUsuarioModel
{
    public array $retorno = [];

    public function __construct(
        array $permissao,
        array $scopePermitido
    ) {
        pp($permissao);
        ppe($scopePermitido);
    }
}
