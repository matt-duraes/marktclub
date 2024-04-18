<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class TipoJuridico extends Status
{
    public const FISICA = 'fisica';
    public const JURIDICA = 'juridica';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::FISICA   => 'Pessoa fisica',
            self::JURIDICA => 'Pessoa jurídica'
        ]);
    }
}
