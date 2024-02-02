<?php

namespace App\Classes\Carteirinha;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_CARTEIRINHA);
        $this->padrao('id_admin_empresa');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
