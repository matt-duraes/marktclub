<?php

use App\Classes\ParceiroCupom\Auditado;
use App\Classes\ParceiroLoja\Categoria;

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
            ->linha('status', 'Status');
    });

    $Painel->status(
        campo: 'status',
        texto: 'Auditado',
        inArray: ['Não auditado'],
        status: 'auditado',
        mensagem: 'Tem certeza que deseja marcar como auditado?',
        cor: 'vermelho'
    );
});

$Painel->replace('status', (new Auditado())->select());
$Painel->replace('categoria', (new Categoria())->select());

return $Painel;
