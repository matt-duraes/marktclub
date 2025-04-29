<?php

namespace App\Classes\Pesquisa;

use Status\Status;

class Experiencia extends Status
{
    public const BOA = 'boa';
    public const REGULAR = 'regular';
    public const RUIM = 'ruim';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BOA     => 'Boa',
            self::REGULAR => 'Regular',
            self::RUIM    => 'Ruim'
        ]);
    }
}
