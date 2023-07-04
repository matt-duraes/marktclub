<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class EmailDisparo extends StatusStatus
{
    public const MARKTCLUB = 'marktclub';
    public const CLIENTE = 'cliente';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::MARKTCLUB  => 'Markt Club',
            self::CLIENTE    => 'Cliente'
        ]);
    }
}
