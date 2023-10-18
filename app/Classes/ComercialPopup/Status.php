<?php

namespace App\Classes\ComercialPopup;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const INATIVO = 'inativo';
    public const EXPIRADO = 'expirado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO    => 'Ativo',
            self::INATIVO  => 'Inativo',
            self::EXPIRADO => 'Expirado'
        ], [
            self::ATIVO    => 'verde',
            self::INATIVO  => 'vermelho',
            self::EXPIRADO => 'amarelo'
        ]);
    }
}
