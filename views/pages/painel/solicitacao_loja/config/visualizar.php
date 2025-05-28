<?php

use App\Classes\SolicitacaoLoja\Status;
use App\Classes\UsuarioCliente\Helper;
use PainelConfig\Visualizar;

$Painel = new Visualizar('solicitacao_loja');

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
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
            ->linha('quemIndicou->nome', 'Nome', permissao: 'solicitacao_loja_empresa')
            ->linha('quemIndicou->cpf', 'CPF', permissao: 'solicitacao_loja_empresa')
            ->linha('quemIndicou->email', 'E-mail', permissao: 'solicitacao_loja_empresa')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/->quemIndicou->id',
                permissao: 'solicitacao_loja_empresa'
            );
    });

    $Painel->bloco(titulo: 'Parceiro (vinculado)', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('parceiro_info', 'Sem parceiro vinculado', permissao: 'parceiro_loja_visualizar')
            ->linha('parceiro_info->titulo_interno', 'Título Interno', permissao: 'parceiro_loja_visualizar')
            ->linha('parceiro_info->nome_fantasia', 'Nome Fantasia', permissao: 'parceiro_loja_visualizar')
            ->linha('parceiro_info->razao_social', 'Razão Social', permissao: 'parceiro_loja_visualizar')
            ->data('parceiro_info->data_prospeccao', 'Data Prospecção', permissao: 'parceiro_loja_visualizar')
            ->linha('parceiro_info->status', 'Status', permissao: 'parceiro_loja_visualizar')
            ->botao(
                'parceiro_link',
                'Ver parceiro',
                link: LINK . '/app/visualizar/parceiro-loja/->parceiro_info->id',
                permissao: 'parceiro_loja_visualizar'
            );
    });

    $Painel->bloco(titulo: 'Dados da solicitação', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de indicação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Sem Interesse',
            inArray: [$Status->nome(Status::SEM_VINCULO)],
            status: Status::CANCELADO,
            mensagem: 'Tem certeza que deseja alterar o status para Sem Interesse?',
            cor: 'vermelho'
        );
});

$Painel->replace(campo: 'status', lista: $Status->select());

return $Painel;
