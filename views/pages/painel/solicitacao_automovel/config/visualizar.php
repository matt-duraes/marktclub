<?php

use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_automovel');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Usuario', callback: function () use ($Painel) {
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
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviar p/ usuário',
            inArray: ['Novo', 'Enviado p/ Empresa', 'Enviado p/ Usuário'],
            status: Status::ENVIADO_USUARIO,
            mensagem: 'Tem certeza que deseja enviar para o usuário?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviar p/ empresa',
            inArray: ['Novo', 'Enviado p/ Empresa', 'Enviado p/ Usuário'],
            status: Status::ENVIADO_EMPRESA,
            mensagem: 'Tem certeza que deseja enviar para a empresa?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Solicitação com problema',
            inArray: ['Novo', 'Enviado p/ Empresa', 'Enviado p/ Usuário'],
            status: Status::PROBLEMA,
            mensagem: 'Tem certeza que deseja finalizar essa solicitação?',
            cor: 'vermelho'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar solicitação',
            inArray: ['Novo', 'Enviado p/ Empresa', 'Enviado p/ Usuário', 'Problema'],
            status: Status::FINALIZADO,
            mensagem: 'Tem certeza que deseja fechar essa solicitação?',
            cor: 'verde'
        );
});

$Painel->replace('status', (new Status())->select());

return $Painel;
