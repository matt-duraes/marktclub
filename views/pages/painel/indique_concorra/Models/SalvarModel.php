<?php

namespace Painel\IndiqueConcorra\Models;

use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        $body['dono'] = sessao('USUARIO.id');
        return $body;
    }
}
