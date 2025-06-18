<?php

namespace App\Classes\UsuarioClienteCodigo;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_USUARIO_CLUBE_CODIGO);
        $this->padrao('status');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
