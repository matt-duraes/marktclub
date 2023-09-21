<?php

namespace Painel\UsuarioEquipe\Models;

use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        if (!empty(sessao('USUARIO.subempresa'))) {
            $body['subempresa'] = sessao('USUARIO.subempresa');
        }
        return $body;
    }
}
