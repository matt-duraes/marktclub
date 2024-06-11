<?php

namespace App\Classes\Silium;

use Status\Status;

class TipoPagamento extends Status
{
    public const DADOS_BANCARIOS = 'dados_bancarios';
    public const PIX = 'pix';
    public const TED = 'ted';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::DADOS_BANCARIOS => 'Dados Bancários',
            self::PIX             => 'Pix',
            self::TED             => 'Ted'
        ]);
    }
}
