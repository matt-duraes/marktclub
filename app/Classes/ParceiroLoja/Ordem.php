<?php

namespace App\Classes\ParceiroLoja;

use Order\Order;

final class Ordem extends Order
{
    public const PAINEL = 'painel';
    public const TITULO_AZ = 'titulo-a-z';
    public const TITULO_ZA = 'titulo-z-a';

    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_LOJA);
        $this->rand();
        $this->campo('titulo-a-z', 'Título A-Z', 'titulo', 'ASC');
        $this->campo('titulo-z-a', 'Título Z-A', 'titulo', 'DESC');
        $this->maisNovo();
        $this->maisVelho();
        $this->campoTexto('painel', 'Painel', 'data_auditoria', 'FIELD(`status`, 2, 1, 4, 3, 5, 6), `data_auditoria` ASC', '<');
    }
}
