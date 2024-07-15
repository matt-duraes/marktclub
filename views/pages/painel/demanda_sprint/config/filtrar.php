<?php

use App\Classes\Demanda\Sprint\Status;

$Painel = new PainelConfig\Filtrar('demanda-sprint');
$Painel
    ->input(
        name: 'titulo',
        label: 'título',
        placeholder: 'Título da sprint'
    )
    ->data(
        name: ['data_inicio', 'data_final'],
        label: 'Data da sprint',
        placeholder: ['Data do começo', 'Data final'],
        separador: '|'
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
