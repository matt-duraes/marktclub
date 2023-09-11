<?php

use App\Classes\ChatbotPerguntas\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('chatbot_categoria');

return $Painel
    ->campo('categoria', 'Catergoria', 'grande')
    ->status('status', 'Status', new Status());
