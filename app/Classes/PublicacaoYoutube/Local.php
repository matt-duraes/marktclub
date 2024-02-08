<?php

namespace App\Classes\PublicacaoYoutube;

use Status\Status as StatusStatus;

class Local extends StatusStatus
{
    public const PRINCIPAL = 'principal';
    public const LISTA = 'lista';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PRINCIPAL  => 'Vídeo principal',
            self::LISTA      => 'Lista de vídeos',
        ]);
    }
}
