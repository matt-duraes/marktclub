<?php

namespace App\Classes\Saude;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const REGISTRADO = 'registrado';
    public const INVALIDO = 'invalido';
    public const ENVIADO = 'enviado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::REGISTRADO => 'Registrado',
            self::INVALIDO   => 'Inválido',
            self::ENVIADO    => 'Enviado'
        ], [
            self::REGISTRADO => 'azul',
            self::INVALIDO   => 'vermelho',
            self::ENVIADO    => 'verde'
        ]);
    }
}
