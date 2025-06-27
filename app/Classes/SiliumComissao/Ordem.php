<?php

namespace App\Classes\SiliumComissao;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SILIUM_COMISSAO);
        $this->status();
        $this->campo('pontuacao-menor', 'Maior pontuação', 'pontuacao', 'DESC');
        $this->campo('pontuacao-maior', 'Menor pontuação', 'pontuacao', 'ASC');
        $this->campo('comissao-menor', 'Maior comissão', 'comissao_usuario', 'DESC');
        $this->campo('comissao-maior', 'Menor comissão', 'comissao_usuario', 'ASC');
        $this->campo('compra-nova', 'Compra mais nova', 'data_compra', 'DESC');
        $this->campo('compra-velha', 'Compra mais velha', 'data_compra', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
    }
}
