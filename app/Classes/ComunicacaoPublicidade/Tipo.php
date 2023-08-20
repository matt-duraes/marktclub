<?php

namespace App\Classes\ComunicacaoPublicidade;

use Status\Status;

class Tipo extends Status
{
    public const HISTORICO = 'historico';
    public const AUTOMOVEL = 'automovel';
    public const LOGIN = 'login';
    public const HOME = 'home';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::HISTORICO => 'Historico',
            self::AUTOMOVEL => 'Automóvel',
            self::HOME      => 'Home',
        ]);
    }
}
