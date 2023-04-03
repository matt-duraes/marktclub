<?php

namespace App\Classes\PontoCvs;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const SOLICITADO = 'solicitado';
    public const APROVADO = 'aprovado';
    public const RECUSADO = 'recusado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::SOLICITADO => 'Solicitado',
                self::APROVADO => 'Aprovado',
                self::RECUSADO => 'Recusado'
            ],
            cor: [
                self::SOLICITADO => 'azul',
                self::APROVADO => 'verde',
                self::RECUSADO => 'preto'
            ]
        );
    }
}
