<?php

$Painel = new PainelConfig\Filtrar('comercial-regra');

$Painel
    ->select(
        name: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        lista: 'empresa'
    );

return $Painel;
