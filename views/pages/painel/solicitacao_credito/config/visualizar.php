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
            ->linha('parcela', 'Parcela')
            ->dinheiro('valor_parcela', 'Valor da parcela')
            ->dataHora('data_criacao', 'Data de criação')
            ->linha('status', 'Status');
    });
});

$Painel
    ->status(
        campo: 'status',
        texto: 'Enviado p/ parceiro',
        inArray: ['Novo'],
        status: 'enviado-parceiro',
        mensagem: 'Tem certeza que deseja alterar o status para enviado para o parceiro?',
        cor: 'verde'
    );

$Painel
    ->status(
        campo: 'status',
        texto: 'Contratado',
        inArray: ['Enviado para parceiro'],
        status: 'contratado',
        mensagem: 'Tem certeza que deseja alterar o status para contratado?',
        cor: 'verde'
    );

$Painel
    ->status(
        campo: 'status',
        texto: 'Cancelado',
        inArray: ['Novo', 'Enviado para parceiro'],
        status: 'cancelado',
        mensagem: 'Tem certeza que deseja alterar o status para cancelado?',
        cor: 'vermelho'
    );

$Painel->replace('operadora', (new Operadora())->select());
$Painel->replace('tipo', (new Tipo())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
