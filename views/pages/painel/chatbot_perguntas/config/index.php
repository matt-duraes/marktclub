<?php

use App\Classes\ChatbotPerguntas\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('chatbot_perguntas', new Ordem());

return $Painel
    ->campo('categoria', 'Catergoria', 'grande')
    ->campo('pergunta', 'Pergunta', 'grande')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());
