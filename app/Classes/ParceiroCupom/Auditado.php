<?php

namespace App\Classes\ParceiroCupom;

use Status\Status as StatusStatus;

final class Auditado extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SIM => 'Sim',
            self::NAO => 'Não'
        ], [
            self::SIM => 'verde',
            self::NAO => 'vermelho'
        ]);
    }
}
