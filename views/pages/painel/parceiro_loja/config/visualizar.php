<?php

use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Visualizar('parceiro_loja');

$Painel
    ->imagemLogo('imagem_logo')
    ->margin(40);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Contato', callback: function () use ($Painel) {
        $Painel
            ->linha('responsavel_nome', 'Nome')
            ->linha('responsavel_cargo', 'Cargo')
            ->email('responsavel_email', 'E-mail')
            ->telefone('responsavel_telefone', 'Telefone');
    });
    $Painel->bloco(titulo: 'Contrato', callback: function () use ($Painel) {
        $Painel
            ->data('data_contrato_inicio', 'Data do contrato')
            ->data('data_contrato_vencimento', 'Data de vencimento')
            ->checked('precisa_aditivo', 'Precisa de aditivo?')
            ->data('data_auditoria', 'Última auditoria');
    });
    $Painel->bloco(titulo: 'Auditoria', callback: function () use ($Painel) {
        $Painel
            ->data('data_auditoria', 'Última auditoria')
            ->checked('data_auditoria_valida', 'Está em dias?')
            ->botao('data_auditoria', 'Fazer auditoria', 'botao_fazer_auditoria');
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Endereço no clube', abrir: true, callback: function () use ($Painel) {
        $Painel
            ->endereco('parceiro_loja', 'clube');
    });
    $Painel->bloco(titulo: 'Contato no clube', abrir: true, callback: function () use ($Painel) {
        $Painel
            ->contato('parceiro_loja', 'clube');
    });
    $Painel->bloco(titulo: 'Endereço no painel', abrir: true, callback: function () use ($Painel) {
        $Painel
            ->endereco('parceiro_loja', 'painel');
    });
    $Painel->bloco(titulo: 'Contato no painel', abrir: true, callback: function () use ($Painel) {
        $Painel
            ->contato('parceiro_loja', 'painel');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel
        ->status(
            campo: 'status',
            texto: 'Concluir',
            inArray: ['prospeccao', 'cancelado', 'problema'],
            status: Status::CONCLUIDO,
            mensagem: 'Tem certeza que deseja concluir essa loja, ao fazer isso ela irá ser publicada no site?',
            cor: 'verde'
        )
        ->status(
            campo: 'status',
            texto: 'Problema',
            inArray: ['concluido'],
            status: Status::PROBLEMA,
            mensagem: 'Tem certeza que deseja colocar essa loja com problema?',
            cor: 'vermelho'
        )
        ->status(
            campo: 'status',
            texto: 'Cancelar',
            inArray: ['concluido', 'problema', 'prospeccao'],
            status: Status::CANCELADO,
            mensagem: 'Tem certeza que deseja cancelar essa loja?',
            cor: 'cinza'
        );
});

$Painel->include('auditoria', 'data_auditoria');
$Painel->css('painel_parceiro_loja_visualizar');
$Painel->js('painel_parceiro_loja_visualizar');

return $Painel;
