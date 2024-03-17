<?php

$Painel = new PainelConfig\Visualizar('parceiro_loja');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
        $Painel
            ->endereco('parceiro_loja', 'principal');
    });
});

$Painel->css('painel_parceiro_loja_visualizar');
$Painel->js('painel_parceiro_loja_visualizar');

return $Painel;
