<?php

use Helpers\ListaHelper;

$Painel = new PainelConfig\Filtrar('solicitacao_premium');

$Painel
    ->select(
        name: 'data',
        titulo: 'Data',
        label: 'Data',
        lista: (new ListaHelper())->add('', 'Escolha uma data')->add(lista: dataListarMesAno(hoje(), '2022-12-01'))->r(),
    );

return $Painel;
