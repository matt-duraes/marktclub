<?php

use App\Classes\ComunicacaoContato\Status;

$Painel = new PainelConfig\Visualizar('comunicacao_contato');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Parceiro', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro.nome', 'Nome');
    });

    $Painel->bloco(titulo: 'Usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->email('email', 'E-mail')
            ->telefone('telefone', 'Telefone')
            ->linha('mensagem', 'Mensagem');
    });

    $Painel->bloco(titulo: 'Dados do contato', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de contato')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Aguardando',
            inArray: ['Novo'],
            status: 'aguardando',
            mensagem: 'Tem certeza que deseja alterar para aguardando?',
            cor: 'verde'
        )
        ->status(
            campo: 'status',
            texto: 'Respondido',
            inArray: ['Aguardando'],
            status: 'respondido',
            mensagem: 'Tem certeza que deseja alterar para respondido?',
            cor: 'verde'
        );
});

$Painel->replace('status', (new Status())->select());

return $Painel;
