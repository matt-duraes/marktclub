<?php

$Painel = new PainelConfig\Filtrar('solicitacao_declaracao');

$Painel
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_inicio', titulo: 'Solicitado em', label: 'Solicitado em')
            ->data(name: 'data_final', titulo: 'Solicitado até', label: 'Solicitado até');
    })
    ->select(name: 'empresa', lista: 'empresa', titulo: 'Empresa', label: 'Empresa');

return $Painel;
