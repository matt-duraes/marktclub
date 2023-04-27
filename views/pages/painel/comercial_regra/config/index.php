<?php

$Painel = new PainelConfig\Index('comercial_empresa');

return $Painel
    ->imagemUsuario()
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_criacao', 'Criado em', 'pequeno', formatar: 'data');
