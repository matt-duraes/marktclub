<?php

namespace App\Classes\ParceiroCupom;

use Status\Status as StatusStatus;

final class Auditado extends StatusStatus
{
    public const NAO = 'nao';
    public const SIM = 'sim';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NAO => 'Não',
            self::SIM => 'Sim'
        ], [
            self::NAO => 'vermelho',
            self::SIM => 'verde'
        ]);
    }
}
