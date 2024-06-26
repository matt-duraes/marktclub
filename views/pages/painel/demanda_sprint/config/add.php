<?php

$Painel = new PainelConfig\Add(app: 'demanda_spring', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', function () use ($Painel) {
        $Painel
            ->input(name: 'nome', label: 'Nome Completo')
            ->cpf(name: 'cpf', label: 'CPF', placeholder: 'CPF')
            ->select(name: 'genero', label: 'Gênero', lista: 'genero')
            ->select(name: 'estado_civil', label: 'Estado Civil', lista: 'estado_civil')
            ->data(name: 'data_nascimento', label: 'Data de nascimento', placeholder: 'Data de Nascimento');
    });
});

return $Painel;
