<?php

$Painel = new PainelConfig\Add('solicitacao_loja');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do Solicitante', function () use ($Painel) {
        $Painel
            ->cpf(
                name: 'cpf',
                label: 'CPF',
                placeholder: 'CPF do Solicitante'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados da Loja', function () use ($Painel) {
        $Painel
            ->input(
                name: 'nome',
                label: 'Nome',
                placeholder: 'Digite o nome',
                obrigatorio: true,
                contador: 100
            )
            ->email(
                name: 'email',
                label: 'E-mail',
                placeholder: 'Digite o e-mail',
                obrigatorio: true
            )
            ->telefone(
                name: 'telefone',
                label: 'Telefone',
                placeholder: 'Digite o telefone'
            )
            ->editor(
                name: 'mensagem',
                label: 'Mensagem',
                placeholder: 'Informe mais detalhes ou informações',
                obrigatorio: true
            );
    });
});

return $Painel;
