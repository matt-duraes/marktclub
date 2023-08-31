<?php

use App\Classes\Geral\Status;
use App\Classes\TextoClube\Tipo;

$Painel = new PainelConfig\Filtrar('texto_clube');

$Painel
    ->select(name: 'tipo', label: 'Tipo', lista: (new Tipo())->select('Escolha uma opção'))
    ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));

$Painel->replace('tipo', (new Tipo())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
