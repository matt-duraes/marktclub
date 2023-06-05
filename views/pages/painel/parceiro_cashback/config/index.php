<?php

use App\Classes\StatusGeral\Status;
use App\Classes\ParceiroCashback\Ordem;

$Painel = new PainelConfig\Index('parceiro_cashback', new Ordem());

return $Painel
    ->campo('titulo', 'Parceiro', 'grande')
    ->campo('data_criacao', 'Criado em', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
