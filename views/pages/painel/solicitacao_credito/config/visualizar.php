<?php

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Classes\SolicitacaoCredito\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_credito');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Usuário', callback: function () use ($Painel) {
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

    $Painel->bloco('Simulação', callback: function () use ($Painel) {
        $Painel
            ->linha('operadora', 'Operadora')
            ->linha('tipo', 'Tipo')
            ->dinheiro('valor_total', 'Valor total')
            ->linha('parcela', 'Qtd. Parcelas')
            ->dinheiro('valor_parcela', 'Valor das parcelas')
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviado p/ Parceiro',
            inArray: ['Novo'],
            status: Status::ENVIADO_PARCEIRO,
            mensagem: 'Tem certeza que deseja alterar o status de enviado para o parceiro?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Contratado',
            inArray: ['Novo', 'Enviado p/ Parceiro'],
            status: Status::CONTRATADO,
            mensagem: 'Tem certeza que deseja alterar o status de contratado?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Cancelado',
            inArray: ['Novo', 'Enviado p/ Parceiro'],
            status: Status::CANCELADO,
            mensagem: 'Tem certeza que deseja alterar o status de cancelado?',
            cor: 'vermelho'
        );
});

$Painel->replace('operadora', (new Operadora())->select());
$Painel->replace('tipo', (new Tipo())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
