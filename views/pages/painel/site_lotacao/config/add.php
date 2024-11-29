<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('site_lotacao', $acao);

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
    $Painel->fieldset('Informações da Lotação', function () use ($Painel, $Status) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Nome Lotação',
                placeholder: 'Nome lotação',
                obrigatorio: true
            )
            ->switch(
                name: 'principal',
                label: 'Principal'
            )
            ->select(
                name: 'status',
                lista: $Status->select('Selecione um status'),
                label: 'Status',
                placeholder: 'Status',
                obrigatorio: true
            );
    });
});

return $Painel;
