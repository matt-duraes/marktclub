<?php

use App\Classes\ParceiroCupom\Auditado;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Visualizar('parceiro_cupom');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Parceiro', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro->nome', 'Parceiro')
            ->linha('parceiro->site', 'Site');
    });

    $Painel->bloco('Cupom', callback: function () use ($Painel) {
        $Painel
            ->linha('descricao', 'Descrição')
            ->linha('cupom', 'Cupom')
            ->linha('desconto', 'Desconto')
            ->linha('categoria', 'Categoria')
            ->linha('link', 'Link')
            ->dataHora('validade', 'Validade')
            ->linha('status', 'Status')
            ->linha('auditado', 'Auditado');
    });

    $Painel->status(
        campo: 'auditado',
        texto: 'Auditado',
        inArray: ['Não auditado'],
        status: 'auditado',
        mensagem: 'Tem certeza que deseja marcar como auditado?',
        cor: 'vermelho'
    );
});

$Painel->replace('auditado', (new Auditado())->select());
$Painel->replace('categoria', (new Categoria())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
