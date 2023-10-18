<?php

namespace App\Classes\ComercialPopup;

use Status\Status;

class BotaoTarget extends Status
{
    public const LINK_INTERNO = '_self';
    public const LINK_EXTERNO = '_blank';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LINK_INTERNO => 'Link Interno',
            self::LINK_EXTERNO => 'Link Externo',
        ], [
            self::LINK_INTERNO => 'verde',
            self::LINK_EXTERNO => 'azul'
        ]);
    }
}
