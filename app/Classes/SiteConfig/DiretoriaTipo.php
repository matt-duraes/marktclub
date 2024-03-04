<?php

namespace App\Classes\SiteConfig;

use Status\Status as StatusStatus;

final class DiretoriaTipo extends StatusStatus
{
    public const LISTA = 'lista';
    public const COLUNA = 'coluna';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LISTA  => 'Lista',
            self::COLUNA => 'Coluna',
        ]);
    }
}
