<?php

namespace App\Classes\ParceiroCupom;

use Status\Status as StatusStatus;

final class Auditado extends StatusStatus
{
    public const NAO_AUDITADO = 'nao-auditado';
    public const AUDITADO = 'auditado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NAO_AUDITADO => 'Não auditado',
            self::AUDITADO => 'Auditado'
        ], [
            self::NAO_AUDITADO => 'vermelho',
            self::AUDITADO => 'verde'
        ]);
    }
}
