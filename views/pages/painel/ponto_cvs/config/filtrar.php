<?php

use App\Classes\PontoCvs\Status;

$Status = new Status();

$Painel = new PainelConfig\Filtrar('ponto_cvs');

$Painel
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite um CPF')
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $Status->select('Escolha uma opção'));

$Painel->replace('status', $Status->select('Escolha uma opção'));

return $Painel;
