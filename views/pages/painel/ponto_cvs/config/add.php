<?php

use App\Classes\PontoCvs\Status;

$Painel = new PainelConfig\Add('ponto_cvs');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', function () use ($Painel) {
        $Painel
        ->input(name: 'voucher', label: 'Voucher')
        ->textarea(name: 'mensagem', label: 'Mensagem para o Usuario')
        ->select(name: 'status', label: 'Status', lista: (new Status())->select());
    });
});

return $Painel;
