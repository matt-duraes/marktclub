<?php

use App\Classes\ParceiroIndicacao\Status;

$Painel = new PainelConfig\Filtrar('parceiro_indicacao');

$Painel
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha uma opção'),
        label: 'Status'
    );

$Painel->replace('status', (new Status())->select());

return $Painel;