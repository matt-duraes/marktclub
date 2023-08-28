<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Filtrar('publicacao_diretoria');

$Painel
    ->input(name: 'pesquisa', label: 'Pesquisa', placeholder: 'Digite uma pesquisa')
    ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));

$Painel->replace('status', (new Status())->select());

return $Painel;
