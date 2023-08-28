<?php

namespace App\Classes\PublicacaoDiretoria;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PUBLICACAO_DIRETORIA);
        $this->campo('ordem', 'Por ordem', 'ordem', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
