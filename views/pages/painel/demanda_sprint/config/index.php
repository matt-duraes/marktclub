<?php

use PainelConfig\Index;
use App\Classes\Demanda\Sprint\Status;

$Painel = new Index('demanda_spring');
$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_inicio', 'Início', 'pequeno', formatar: Index::FORMATAR_DATA)
    ->campo('data_final', 'Termino', 'pequeno', formatar: Index::FORMATAR_DATA)
    ->status(campo: 'status', nome: 'Status', status: new Status());
return $Painel;
