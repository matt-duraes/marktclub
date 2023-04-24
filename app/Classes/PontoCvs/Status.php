<?php

namespace App\Classes\PontoCvs;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const SOLICITADO = 'solicitado';
    public const APROVADO = 'aprovado';
    public const RECUSADO = 'recusado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SOLICITADO => 'Solicitado',
            self::APROVADO   => 'Aprovado',
            self::RECUSADO   => 'Recusado'
        ], [
            self::SOLICITADO => 'azul',
            self::APROVADO   => 'verde',
            self::RECUSADO   => 'preto'
        ]);
    }
}
