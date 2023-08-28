<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoNoticia\Ordem;

$Painel = new PainelConfig\Index('publicacao_noticia', new Ordem());
return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_inicio', 'Publicada em', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
