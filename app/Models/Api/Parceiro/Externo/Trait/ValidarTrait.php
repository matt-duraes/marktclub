<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

trait ValidarTrait
{
    private function validarRequest()
    {
        $this->validarPropriedade('
            pagina|Página|obrigatorio|vazio|valido
            quantidade|Quantidade|valido
            data_criacao_de|Data de criação de|valido
            data_criacao_ate|Data de criação ate|valido
            status|Status|valido
        ');
    }
}
