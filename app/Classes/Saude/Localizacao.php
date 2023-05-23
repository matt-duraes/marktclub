<?php

namespace App\Classes\Saude;

use Status\Status;

class Localizacao extends Status
{
    public const BRASILIA = 'brasilia';
    public const SALVADOR = 'salvador';
    public const RIO_DE_JANEIRO = 'rio_de_janeiro';
    public const SAO_PAULO = 'sao_paulo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BRASILIA       => 'Brasília',
            self::SALVADOR       => 'Salvador',
            self::SAO_PAULO      => 'São Paulo',
            self::RIO_DE_JANEIRO => 'Rio de Janeiro'
        ]);
    }
}
