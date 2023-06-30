<?php

namespace App\Classes\ParceiroLoja;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const CONCLUIDO = 'concluido';
    public const PREMIUM = 'premium';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            lista: [
                self::CONCLUIDO => 'Concluído',
                self::PREMIUM => 'Premium'
            ],
            numero: [4,5]
        );
    }
}
