<?php

$Painel = new PainelConfig\Filtrar('painel_config');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa'
    )
    ->input(
        name: 'titulo',
        titulo: 'Título Interno',
        label: 'Título Interno',
        placeholder: 'Título Interno'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Criado de',
                label: 'Criado de',
                placeholder: 'Criado de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Criado até',
                label: 'Criado até',
                placeholder: 'Criado até'
            );
    });

return $Painel;
