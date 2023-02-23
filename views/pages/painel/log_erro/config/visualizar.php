<?php

use System\Classes\LogErro\Status;

$Painel = new PainelConfig\Visualizar('log_erro');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados do erro', callback: function () use ($Painel) {
        $Painel
            ->linha('mensagem', 'Mensagem')
            ->linha('arquivo', 'Arquivo')
            ->linha('linha', 'Linha')
            ->linha('quantidade', 'Quantidade')
            ->linha('status_http', 'Status HTTP')
            ->linha('codigo', 'Código')
            ->array('trace', 'Trace')
            ->data('data_criacao', 'Data da criação')
            ->linha('status', 'Status');
    });
    $Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar erro',
            inArray: ['Novo'],
            mensagem: 'Tem certeza que deseja finalizar esse erro?',
            cor: 'verde',
            status: 'corrigido'
        );
});

// Lista de status
$Painel->replace(campo: 'status', lista: (new Status)->select());

return $Painel;
