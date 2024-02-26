<?php

namespace System\Classes\Contato;

use Status\Status;

class Tipo extends Status
{
    public const TELEFONE = 'telefone';
    public const EMAIL = 'email';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::TELEFONE  => 'Telefone',
            self::EMAIL     => 'E-mail',
        ]);
    }
}
