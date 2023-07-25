<?php

namespace App\Classes\ComercialFatura;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ABERTA = 'aberta';
    public const VENCIDA = 'vencida';
    public const PAGA = 'paga';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ABERTA  => 'Aberta',
            self::VENCIDA => 'Vencida',
            self::PAGA    => 'Paga'
        ], [
            self::ABERTA  => 'azul',
            self::VENCIDA => 'vermelho',
            self::PAGA    => 'verde'
        ]);
    }
}
