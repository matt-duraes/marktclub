<?php

use App\Classes\Saude\Status;

$Painel = new PainelConfig\Filtrar('publicacao_pagina');

$Painel
    ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));

$Painel->replace('status', (new Status())->select());

return $Painel;
