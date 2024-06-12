<?php

$Painel = new PainelConfig\Download('parceiro_loja');

$Painel
    ->bloco('Dados do usuario', function () use ($Painel) {
        $Painel
            ->campo('usuario_nome', 'Nome')
            ->campo('usuario_cpf', 'CPF');
    })
    ->bloco('Dados do clube', function () use ($Painel) {
        $Painel
            ->campo('empresa_titulo', 'Titulo');
    })
    ->bloco('Dados da solicitação', function () use ($Painel) {
        $Painel
            ->campo('nome', 'Nome')
            ->campo('telefone', 'Telefone')
            ->campo('email', 'Email')
            ->campo('mensagem', 'Mensagem');
    })
    ->bloco('Outros dados', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_atualizacao', 'Data de atualização')
            ->campo('status', 'Status');
    });

return $Painel;
