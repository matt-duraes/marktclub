<?php

use App\Classes\UsuarioCliente\Ordem;

$Painel = new PainelConfig\Index('view_pagina', new Ordem());
$Painel->campo('titulo', 'Título', 'grande');
return $Painel;
