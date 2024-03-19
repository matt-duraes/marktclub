<?php

$Painel = new PainelConfig\Visualizar('parceiro_loja');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Endereço no clube', callback: function () use ($Painel) {
        $Painel
            ->endereco('parceiro_loja', 'clube');
    });
    // $Painel->bloco(titulo: 'Contato do clube', callback: function () use ($Painel) {
    //     $Painel
    //         ->contato('parceiro_loja', 'clube');
    // });
});

$Painel->css('painel_parceiro_loja_visualizar');
$Painel->js('painel_parceiro_loja_visualizar');

return $Painel;
