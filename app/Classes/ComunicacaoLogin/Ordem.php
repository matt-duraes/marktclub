<?php

namespace App\Classes\ComunicacaoLogin;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_COMUNICACAO_LOGIN);
        $this->padrao('status');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
