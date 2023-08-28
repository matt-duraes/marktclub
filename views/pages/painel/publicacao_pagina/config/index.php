<?php

use App\Classes\PublicacaoPagina\Ordem;

$Painel = new PainelConfig\Index('publicacao_pagina', new Ordem());
return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->dataCriacao();
