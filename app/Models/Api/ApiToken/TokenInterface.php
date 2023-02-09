<?php

namespace App\Models\Api\ApiToken;

interface TokenInterface
{
    public function pegarToken(): array;
}
