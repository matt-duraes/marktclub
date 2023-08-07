<?php

namespace App\Classes\SistemaEndereco;

use Status\Status;

class Tabela extends Status
{
    public const LOJA = 'parceiro';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LOJA       => 'Parceiro Loja',
        ]);
    }
}
