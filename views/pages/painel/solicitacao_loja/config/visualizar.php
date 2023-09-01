<?php

use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_loja');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Indicação', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->linha('email', 'E-mail')
            ->linha('telefone', 'Telefone', 'telefone')
            ->linha('mensagem', 'Mensagem')
            ->linha('status', 'Status');
    });
});

$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
