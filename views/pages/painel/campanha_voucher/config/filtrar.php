<?php

use App\Classes\CampanhaVoucher\Status;
use PainelConfig\Filtrar;

$Painel = new Filtrar('campanha_voucher');

$Painel
    ->numero(
        name: 'quantidade',
        titulo: 'Quantidade',
        label: 'Quantidade',
        placeholder: 'Quantidade'
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
