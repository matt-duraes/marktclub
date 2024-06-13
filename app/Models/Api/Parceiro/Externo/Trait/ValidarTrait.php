<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

use Helpers\ValidarHelper;

trait ValidarTrait
{
    private function validarRequest()
    {
        (new ValidarHelper())->validar('
            pagina|Página|obrigatorio|vazio|valido
            quantiadade|Quantidade|valido
            data_criacao_de|Data de criação de|valido
            data_criacao_ate|Data de criação ate|valido
            status|Status|valido
        ');
    }
}
