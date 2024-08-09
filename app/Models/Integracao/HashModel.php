<?php

namespace App\Models\Integracao;

final class HashModel
{
    public array $dado = [];

    public function __construct(
        private string $hash
    ) {
    }
}
