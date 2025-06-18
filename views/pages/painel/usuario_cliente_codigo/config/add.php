<?php

use App\Classes\Geral\Status;
use PainelConfig\Add;

$Painel = new Add(app: 'usuario_cliente_codigo', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Informações da Empresa', function () use ($Painel) {
        $Painel
            ->select(
                name: 'empresa',
                lista: 'empresa',
                label: 'Empresa',
                placeholder: 'Empresa'
            )
            ->select(
                name: 'subempresa',
                lista: 'subempresa',
                label: 'Subempresa',
                placeholder: 'Subempresa',
                todasSubempresa: true
            );
    });

    $Painel->fieldset('Informações do Código', function () use ($Painel) {
        $Painel
            ->input(
                name: 'codigo',
                label: 'Código',
                placeholder: 'Código',
                ajuda: 'Código que será usado para primeiro acesso ao sistema'
            )
            ->select(
                name: 'status',
                lista: (new Status())->select('Selecione um status'),
                label: 'Status',
                placeholder: 'Status'
            );
    });
});

return $Painel;
