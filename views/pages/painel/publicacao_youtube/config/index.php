<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoYoutube\Ordem;

$Painel = new PainelConfig\Index(app: 'publicacao_youtube', ordem: new Ordem());

return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_inicio', 'Publicada em', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
