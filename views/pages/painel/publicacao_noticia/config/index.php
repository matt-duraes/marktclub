<?php

use App\Classes\StatusGeral\Status;
use App\Classes\PublicacaoNoticia\Ordem;

$Painel = new PainelConfig\Index('publicacao_noticia', new Ordem());
return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_publicacao_inicio', 'Publicada em', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
