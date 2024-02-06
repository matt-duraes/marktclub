<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Filtrar(app: 'publicacao_youtube');

$Painel
    ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));
$Painel->replace('status', (new Status())->select());

return $Painel;
