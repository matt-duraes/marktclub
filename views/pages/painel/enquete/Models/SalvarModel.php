<?php

namespace Painel\Enquete\Models;

use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        if (!array_key_exists('id', $body)) {
            $body['tipo'] = 'enquete';
        }
        return $body;
    }
}
