<?php

namespace PainelApp\login\Models\Trait;

use stdClass;

trait TokenTrait
{
    private stdClass $token;

    public function pegarToken(): stdClass
    {
        return $this->token;
    }
}
