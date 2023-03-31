<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class Estabelecimento extends Status
{
    public const FISICO = 'fisico';
    public const ONLINE = 'online';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::FISICO => 'Físico',
                self::ONLINE => 'On-line'
            ]
        );
    }
}
