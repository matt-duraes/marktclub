<?php

namespace Painel\SiliumDeposito\Models;

use App\Classes\SiliumDeposito\TipoOperacao;
use System\Interface\PainelSalvarAddInterface;

final class SalvarModel implements PainelSalvarAddInterface
{
    public function body(array $body): array
    {
        $body['tipo_operacao'] = TipoOperacao::DEPOSITO;
        return $body;
    }
}
