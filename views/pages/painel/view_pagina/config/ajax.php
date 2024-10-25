<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('html', function () use ($Painel) {
        $Painel
            ->request(['!html'])
            ->permissao('view_pagina_index')
            ->metodo('put')
            ->rota('/view-pagina/{id}');
    });
