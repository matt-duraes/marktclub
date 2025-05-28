<?php

use PainelConfig\Download;

$Painel = new Download('solicitacao_loja');

$Painel
    /*->bloco('Dados da Empresa', function () use ($Painel) {
        $Painel
            ->campo('empresa_titulo', 'Titulo');
    })*/
    ->bloco('Dados da Indicação', function () use ($Painel) {
        $Painel
            ->campo('nome', 'Nome')
            ->campo('telefone', 'Telefone')
            ->campo('email', 'Email')
            ->campo('mensagem', 'Mensagem');
    })
    ->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_atualizacao', 'Data de atualização')
            ->campo('status', 'Status');
    });

return $Painel;
