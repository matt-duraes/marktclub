<?php

namespace App\Classes\SiliumSaldo;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SILIUM_SALDO);
        $this->padrao('pontuacao');
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('pontuacao-menores', 'Menores Pontos', 'saldo_silium', 'ASC');
        $this->campo('pontuacao-maiores', 'Maiores Pontos', 'saldo_silium', 'DESC');
    }
}
