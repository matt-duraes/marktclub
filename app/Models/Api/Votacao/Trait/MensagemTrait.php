<?php

namespace App\Models\Api\Votacao\Trait;

trait MensagemTrait
{
    private function mensagemBloqueado(): void
    {
        mensagemErro('Erro!', 'Essa item está bloqueada para edição.');
    }
}
