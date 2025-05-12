<?php

namespace App\Classes\EnqueteMercado;

use Status\Status;

class Importancia extends Status
{
    public const QUALIDADE = 'qualidade';
    public const PRECO = 'preco';
    public const REPUTACAO = 'reputacao';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::QUALIDADE => 'Qualidade',
            self::PRECO     => 'Preço',
            self::REPUTACAO => 'Reputação'
        ]);
    }
}
