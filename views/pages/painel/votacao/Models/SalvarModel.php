<?php

namespace Painel\Votacao\Models;

use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        $body['tipo'] = 'votacao';
        return $body;
    }
}
