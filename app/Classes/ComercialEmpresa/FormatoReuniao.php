<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class FormatoReuniao extends StatusStatus
{
    public const PRESENCIAL = 'presencial';
    public const ONLINE = 'online';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PRESENCIAL => 'Presencial',
            self::ONLINE     => 'Online'
        ]);
    }
}
