<?php

namespace App\Classes\ComunicacaoPublicidade;

use Status\Status;

class Tipo extends Status
{
    public const HISTORICO = 'historico';
    public const AUTOMOVEL = 'automovel';
    public const LOGIN = 'login';
    public const HOME = 'home';
    public const SAMSUNG = 'samsung';
    public const TURISMO = 'turismo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::HISTORICO    => 'Stories',
            self::AUTOMOVEL    => 'Automóvel',
            self::HOME         => 'Home',
            self::SAMSUNG      => 'Samsung',
            self::TURISMO      => 'Turismo',
        ]);
    }
}
