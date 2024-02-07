<?php

namespace App\Classes\PublicacaoNoticia;

use Status\Status as StatusStatus;

class Local extends StatusStatus
{
    public const PRINCIPAL = 'principal';
    public const LISTA = 'lista';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PRINCIPAL  => 'Notícia principal',
            self::LISTA      => 'Lista de notícia',
        ]);
    }
}
