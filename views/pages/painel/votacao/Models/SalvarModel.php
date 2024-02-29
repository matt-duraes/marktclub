<?php

namespace Painel\Votacao\Models;

use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        if (!array_key_exists('id', $body)) {
            $body['tipo'] = 'votacao';
        }
        return $body;
    }
}
