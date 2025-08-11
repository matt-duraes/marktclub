<?php

namespace App\Classes\CampanhaVoucher;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_CAMPANHA_VOUCHER);
        $this->padrao('status');
        $this->asc('status-nao-resgatado', 'Não Regatados', 'status');
        $this->desc('status-vencido', 'Vencidos', 'status');
    }
}
