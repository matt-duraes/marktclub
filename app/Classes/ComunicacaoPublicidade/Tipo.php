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
    public const LG = 'lg';
    public const TURISMO = 'turismo';
    public const CARTAO_SAMSUNG = 'cartao-samsung';
    public const MANOLE = 'manole';
    public const CINEMA = 'cinema';
    public const NETSHOES_VOUCHER = 'netshoes-voucher';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::HISTORICO      => 'Stories',
            self::AUTOMOVEL      => 'Automóvel',
            self::HOME           => 'Home',
            self::SAMSUNG        => 'Samsung',
            self::TURISMO        => 'Turismo',
            self::CARTAO_SAMSUNG => 'Cartão Samsung',
            self::LG             => 'LG',
            self::MANOLE         => 'Manole',
            self::CINEMA         => 'Cinema',
            self::NETSHOES_VOUCHER => 'Netshoes Voucher',
        ]);
    }
}
