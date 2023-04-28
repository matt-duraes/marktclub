<?php

use Helpers\DataHelper;

$Painel = new PainelConfig\Filtrar('solicitacao_premium');

$Painel
    ->select(
        name: 'data',
        titulo: 'Data',
        label: 'Data',
        lista: []
    );

return $Painel;
