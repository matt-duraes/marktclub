<?php

namespace App\Classes\PainelTradutor;

use Status\Status;

class Idiomas extends Status
{
    public const PORTUGUES = 'pt_br';
    public const INGLES = 'en';
    public const ESPANHOL = 'es';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PORTUGUES => 'Português',
            self::INGLES    => 'Inglês',
            self::ESPANHOL  => 'Espanhol'
        ]);
    }
}
