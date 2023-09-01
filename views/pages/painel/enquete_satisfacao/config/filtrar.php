<?php

use App\Classes\EnqueteSatisfacao\Status;

$Painel = new PainelConfig\Filtrar('enquete_satisfacao');

$Painel
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha uma opção'),
        label: 'Status'
    );

$Painel->replace('status', (new Status())->select());

return $Painel;
