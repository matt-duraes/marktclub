<?php

$Painel = new PainelConfig\Visualizar('solicitacao_automovel');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: "Usuario", callback: function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->linha('usuario->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_VISUALIZAR
            );
    });
    $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
        $Painel
           ->linha('endereco_estado', 'Estado')
           ->linha('endereco_cidade', 'Cidade');
    });

    $Painel->bloco(titulo: 'Automóvel', callback: function () use ($Painel) {
        $Painel
           ->linha('montadora', 'Montadora')
           ->linha('modelo', 'Modelo')
           ->linha('versao', 'Versão')
           ->linha('cor', 'Cor');
    });

    $Painel->bloco(titulo: 'Mensagem', callback: function () use ($Painel) {
        $Painel
           ->linha('mensagem', 'Mensagem');
    });

    $Painel->bloco(titulo: 'Dados da solicitação', callback: function () use ($Painel) {
        $Painel
            ->linha('data_criacao', 'Data de criação', formatar: 'datahora')
            ->linha('data_atualizacao', 'Data de atualização', formatar: 'datahora')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviado p/ empresa',
            inArray: ['novo'],
            status: 'enviado-empresa',
            mensagem: 'Tem certeza que deseja alterar o status para enviado para a empresa?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviado p/ usuário',
            inArray: ['novo', 'enviado-empresa'],
            status: 'enviado-usuario',
            mensagem: 'Tem certeza que deseja alterar o status para enviado para o usuário?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Solicitação com problema',
            inArray: ['novo', 'enviado-empresa', 'enviado-usuario'],
            status: 'problema',
            mensagem: 'Tem certeza que deseja finalizar essa solicitação com o status problema?',
            cor: 'vermelho'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Solicitação finalizada',
            inArray: ['novo', 'enviado-empresa', 'enviado-usuario'],
            status: 'finalizado',
            mensagem: 'Tem certeza que deseja finalizar essa solicitação?',
            cor: 'vermelho'
        );
});

return $Painel;
