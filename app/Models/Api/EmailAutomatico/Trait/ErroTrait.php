<?php

namespace App\Models\Api\EmailAutomatico\Trait;

trait ErroTrait
{
    private function erroGeral()
    {
        mensagemStatus(500);
    }
}
