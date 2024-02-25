<?php

namespace App\Classes\Votacao\Dado;

use Status\Status;

final class Tipo extends Status
{
    public const VOTACAO = 'votacao';
    public const ENQUETE = 'enquete';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::VOTACAO => 'Votação',
            self::ENQUETE => 'Dependente',
        ]);
    }
}
