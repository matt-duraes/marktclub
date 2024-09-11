<?php

$Painel = new PainelConfig\Visualizar('view_pagina');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados pessoais', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo', 'Título')
            ->linha('url', 'URI');
    });
});

$Painel->include('lista');
$Painel->js('painel_view_pagina_lista');
$Painel->css('painel_view_pagina_lista');

return $Painel;
