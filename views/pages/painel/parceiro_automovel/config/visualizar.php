<?php

$Painel = new PainelConfig\Visualizar('parceiro_automovel');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados pessoais', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo', 'Modelo');
    });
});

$Painel->include('versao');
$Painel->css('painel_parceiro_automovel_visualizar');
$Painel->js('painel_parceiro_automovel_visualizar');

return $Painel;
