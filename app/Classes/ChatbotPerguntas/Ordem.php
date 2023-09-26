<?php

namespace App\Classes\ChatbotPerguntas;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_CHATBOT_PERGUNTAS);
        $this->maisNovo();
        $this->maisVelho();
    }
}
