<?php

namespace App\Classes\ParceiroLoja;

use Order\Order;

final class Ordem extends Order
{
    public const PAINEL_ASC = 'painel-asc';
    public const PAINEL_DESC = 'painel-desc';
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
        $this->campoTexto('painel-asc', 'Painel ASC', 'data_auditoria', '`status` DESC, `data_auditoria` ASC, `data_criacao` ASC', '<');
        $this->campoTexto('painel-desc', 'Painel DESC', 'data_auditoria', '`status` ASC,`data_criacao` DESC, `data_auditoria` DESC', '>');
    }
}
