<?php

use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_declaracao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Empresa', callback: function () use ($Painel) {
        $Painel
            ->linha('empresa->nome', 'Nome')
            ->botao(
                'empresa_link',
                'Ver empresa',
                link: LINK . '/app/visualizar/comercial-empresa/empresa->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
            );
    });

    $Painel->bloco('Usuário', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('usuario', 'Usuário foi deletado e não existe mais')
            ->linha('usuario->nome', 'Nome')
            ->email('usuario->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Parceiro', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro.nome', 'Nome')
            ->linha('modelo', 'Modelo')
            ->linha('versao', 'Versão');
    });

    $Painel->bloco(titulo: 'Dados da declaração', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviar p/ usuário',
            inArray: ['Novo', 'Enviado p/ Empresa'],
            status: Status::ENVIADO_USUARIO,
            mensagem: 'Tem certeza que deseja enviar para o usuário?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviar p/ empresa',
            inArray: ['Novo', 'Enviado p/ Usuário'],
            status: Status::ENVIADO_EMPRESA,
            mensagem: 'Tem certeza que deseja enviar para a empresa?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Declaração com problema',
            inArray: ['Enviado p/ Empresa', 'Enviado p/ Usuário'],
            status: Status::PROBLEMA,
            mensagem: 'Tem certeza que deseja finalizar essa solicitação?',
            cor: 'vermelho'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar solicitação',
            inArray: ['Enviado p/ Empresa', 'Enviado p/ Usuário', 'Problema'],
            status: Status::FINALIZADO,
            mensagem: 'Tem certeza que deseja fechar essa solicitação?',
            cor: 'verde'
        );
});

$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
