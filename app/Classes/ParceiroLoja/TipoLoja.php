<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class TipoLoja extends Status
{
    public const LOJA = 'loja';
    public const AUTOMOVEL = 'automovel';
    public const FARMACIA = 'farmacia';
    public const PREMIUM = 'premium';
    public const LABORATORIO = 'laboratorio';
    public const CASHBACK = 'cashback';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LOJA        => 'Loja',
            self::AUTOMOVEL   => 'Automóvel',
            self::FARMACIA    => 'Farmácia',
            self::PREMIUM     => 'Premium',
            self::LABORATORIO => 'Laboratório',
            self::CASHBACK    => 'Cashback',
        ]);
    }
}
