<?php

$Painel = new PainelConfig\Visualizar('parceiro_loja');

$Painel
    ->imagemLogo('link_logo')
    ->titulo('titulo')
    ->margin(40);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Endereço no clube', callback: function () use ($Painel) {
        $Painel
            ->endereco('parceiro_loja', 'clube');
    });
    // $Painel->bloco(titulo: 'Contato no clube', callback: function () use ($Painel) {
    //     $Painel
    //         ->contato('parceiro_loja', 'clube');
    // });
    // $Painel->bloco(titulo: 'Endereço no painel', callback: function () use ($Painel) {
    //     $Painel
    //         ->endereco('parceiro_loja', 'painel');
    // });
    // $Painel->bloco(titulo: 'Contato no painel', callback: function () use ($Painel) {
    //     $Painel
    //         ->contato('parceiro_loja', 'painel');
    // });
});

$Painel->css('painel_parceiro_loja_visualizar');
$Painel->js('painel_parceiro_loja_visualizar');

return $Painel;
