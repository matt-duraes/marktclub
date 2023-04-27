<?php

$Painel = new PainelConfig\Index('comercial_regra');

return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_criacao', 'Criado em', 'pequeno', formatar: 'data');
