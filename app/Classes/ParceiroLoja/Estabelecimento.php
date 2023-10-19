<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class Estabelecimento extends Status
{
    public const FISICO = 'fisico';
    public const ONLINE = 'online';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::FISICO => 'Físico',
            self::ONLINE => 'On-line'
        ]);
    }
}
