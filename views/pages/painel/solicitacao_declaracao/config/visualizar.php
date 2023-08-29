<?php

use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_declaracao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Parceiro', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro.titulo', 'Nome')
            ->linha('modelo', 'Modelo')
            ->linha('versao', 'Versão');
    });

    $Painel->bloco(titulo: 'Usuário', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('usuario', 'Usuário foi deletado e não existe mais')
            ->linha('usuario.nome', 'Nome')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco(titulo: 'Dados da declaração', callback: function () use ($Painel) {
        $Painel
            ->linha('data_criacao', 'Data de criação', formatar: 'datahora')
            ->linha('data_atualizacao', 'Data de atualização', formatar: 'datahora')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviar usuário',
            inArray: ['Novo'],
            status: 'enviado-usuario',
            mensagem: 'Tem certeza que deseja enviar para o usuário?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviar empresa',
            inArray: ['Novo'],
            status: 'enviado-empresa',
            mensagem: 'Tem certeza que deseja enviar para a empresa?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Declaração com problema',
            inArray: ['Novo', 'enviado-empresa', 'enviado-usuario'],
            status: 'problema',
            mensagem: 'Tem certeza que deseja finalizar essa solicitação?',
            cor: 'vermelho'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar solicitação',
            inArray: ['novo', 'enviado-empresa', 'enviado-usuario'],
            status: 'finalizado',
            mensagem: 'Tem certeza que deseja fechar essa solicitação?',
            cor: 'verde'
        );
});

//$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
