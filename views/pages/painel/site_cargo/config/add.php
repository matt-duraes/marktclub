<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('site_cargo', $acao);

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
    $Painel->fieldset('Informações do Cargo', function () use ($Painel, $Status) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Nome do Cargo',
                placeholder: 'Nome do Cargo',
                obrigatorio: true
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
