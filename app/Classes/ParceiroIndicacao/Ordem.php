<?php

namespace App\Classes\ParceiroIndicacao;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_INDICACAO);
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('status', 'Status', 'status', 'ASC');
    }
}