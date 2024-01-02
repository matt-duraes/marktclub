<?php

use App\Classes\SolicitacaoContato\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_contato');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Empresa', callback: function () use ($Painel) {
        $Painel
            ->linha('empresa.nome', 'Nome')
            ->botao(
                'empresa_link',
                'Ver empresa',
                link: LINK . '/app/visualizar/comercial-empresa/empresa->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
            );
    });

    $Painel->bloco('Usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->email('email', 'E-mail')
            ->telefone('telefone', 'Telefone')
            ->linha('mensagem', 'Mensagem');
    });

    $Painel->bloco('Dados do contato', callback: function () use ($Painel) {
        $Painel
            ->linha('tipo', 'Tipo de contato')
            ->linha('local', 'Local de contato')
            ->dataHora('data_criacao', 'Data de contato')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Aguardando',
            inArray: ['Novo'],
            status: Status::AGUARDANDO,
            mensagem: 'Tem certeza que deseja alterar para aguardando?',
            cor: 'verde'
        )
        ->status(
            campo: 'status',
            texto: 'Respondido',
            inArray: ['Aguardando'],
            status: Status::RESPONDIDO,
            mensagem: 'Tem certeza que deseja alterar para respondido?',
            cor: 'verde'
        );
});

$Painel
    ->replace('status', (new Status())->select());

return $Painel;
