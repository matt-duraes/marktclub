<?php

$Painel = new PainelConfig\Visualizar('demanda_spring');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados pessoais', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->cpf('cpf', 'CPF')
            ->linha('rg', 'RG')
            ->linha('estado_civil', 'Estado Civil')
            ->linha('genero', 'Gênero')
            ->data('data_nascimento', 'Data de nascimento');
    });
});

return $Painel;
