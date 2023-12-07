<?php

namespace App\Classes\LoginClube;

use Status\Status;

final class Tipo extends Status
{
    public const TITULAR = 'titular';
    public const DEPENDENTE = 'dependente';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::TITULAR    => 'Titular',
            self::DEPENDENTE => 'Dependente',
        ]);
    }
}
