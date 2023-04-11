<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status;

final class Tipo extends Status
{
    public const LOJA = 'loja';
    public const AUTOMOVEL = 'automovel';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::LOJA => 'Loja',
            self::AUTOMOVEL => 'Automóvel'
        ]);
    }
}
