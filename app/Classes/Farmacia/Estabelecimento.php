<?php

namespace App\Classes\Farmacia;

use Status\Status;

final class Estabelecimento extends Status
{
    public const PRESENCIAL = 'presencial';
    public const ONLINE = 'online';
    public const TODOS = 'todos';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PRESENCIAL      => 'Presencial',
            self::ONLINE          => 'On-line',
            self::TODOS           => 'Todos',
        ]);
    }
}
