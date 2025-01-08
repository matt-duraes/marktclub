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
            ->linha('url', 'URL')
            ->array('trace', 'Trace')
            ->dataHora('data_criacao', 'Data da criação')
            ->dataHora('data_atualizacao', 'Data de atualização')
            ->linha('status', 'Status');
    });
    $Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar erro',
            inArray: ['Novo'],
            status: 'corrigido',
            mensagem: 'Tem certeza que deseja finalizar esse erro?',
            cor: 'verde'
        );
});

// Lista de status
$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
