<?php

namespace App\Classes\ParceiroEasylive;

use Status\Status;

final class Tipo extends Status
{
    public const SHOW_NACIONAL = 'show-nacional';
    public const SHOW_INTERNACIONAL = 'show-internacional';
    public const CINEMA = 'cinema';
    public const CORRIDA = 'corrida';
    public const PASSEIO_TURISTICO = 'passeio-turistico';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SHOW_NACIONAL      => 'Show Nacional',
            self::SHOW_INTERNACIONAL => 'Show Internacional',
            self::CINEMA             => 'Cinema',
            self::CORRIDA            => 'Corrida',
            self::PASSEIO_TURISTICO  => 'Passeio Turistico',
        ]);
    }
}
