<?php

use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Filtrar('comercial_subempresa');

$Painel
    ->input(
        name: 'titulo',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Digite o título'
    );

return $Painel;
