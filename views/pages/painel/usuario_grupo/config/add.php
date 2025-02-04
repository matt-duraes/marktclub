<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('usuario_grupo', $acao);

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
    $Painel->fieldset('Informações da origem', function () use ($Painel, $Status) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'TItulo',
                placeholder: 'Titulo',
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
