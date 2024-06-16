<?php

namespace Painel\ParceiroExterno\Models;

use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements
    PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        if (!empty($body['titulo_interno'])) {
            $slug = sessao('EMPRESA')['slug'] ?? '';
            $body['titulo_interno'] = $body['titulo_interno'] . ' - ' . $slug;
        }
        return $body;
    }
}
