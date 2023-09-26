<?php

use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_loja');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Indicação', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->email('email', 'E-mail')
            ->telefone('telefone', 'Telefone')
            ->linha('origem', 'Origem')
            ->linha('mensagem', 'Mensagem');
    });
    $Painel->bloco('Usuário que indicou', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('quem_indicou', 'Usuário foi deletado e não existe mais.')
            ->linha('quem_indicou->nome', 'Nome')
            ->cpf('quem_indicou->cpf', 'CPF')
            ->email('quem_indicou->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/->quem_indicou->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Em Andamento',
            inArray: [Status::NOVO],
            status: Status::ANDAMENTO,
            mensagem: 'Tem certeza que deseja alterar o Status para Em Andamento?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Concluir',
            inArray: [Status::ANDAMENTO],
            status: Status::CONCLUIDO,
            mensagem: 'Tem certeza que deseja alterar o Status para Concluído?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Cancelar',
            inArray: [Status::ANDAMENTO],
            status: Status::CANCELADO,
            mensagem: 'Tem certeza que deseja alterar o Status para Cancelado?',
            cor: 'vermelho'
        );
});

$Painel->replace(campo: 'origem', lista: (new Origem())->select());

return $Painel;
