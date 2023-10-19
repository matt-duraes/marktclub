<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status;

final class Tipo extends Status
{
    public const LOJA = 'loja';
    public const AUTOMOVEL = 'automovel';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LOJA      => 'Loja',
            self::AUTOMOVEL => 'Automóvel'
        ]);
    }
}
