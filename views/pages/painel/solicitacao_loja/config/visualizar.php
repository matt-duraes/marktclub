<?php

use App\Classes\SolicitacaoLoja\Status;
use App\Classes\UsuarioCliente\Helper;
use PainelConfig\Visualizar;

$Painel = new Visualizar('solicitacao_loja');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Indicação', callback: function () use ($Painel) {
        $Painel
            ->linha('origemIndicacao', 'Empresa')
            ->linha('nome', 'Nome')
            ->email('email', 'E-mail')
            ->telefone('telefone', 'Telefone')
            ->linha('mensagem', 'Mensagem');
    });

    $Painel->bloco('Usuário que indicou', callback: function () use ($Painel) {
        $Painel
            ->linha('quemIndicou->nome', 'Nome')
            ->cpf('quemIndicou->cpf', 'CPF')
            ->email('quemIndicou->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/->quemIndicou->id',
                permissao: Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco(titulo: 'Parceiro (vinculado)', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro->titulo_interno', 'Título Interno')
            ->linha('parceiro->nome_fantasia', 'Nome Fantasia')
            ->email('parceiro->razao_social', 'Razão Social')
            ->linha('parceiro->status', 'Status');
    });

    $Painel->bloco(titulo: 'Dados da solicitação', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de indicação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });
});

$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
