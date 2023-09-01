<?php

namespace System\Classes\Endereco;

use Status\Status;

class Tipo extends Status
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
