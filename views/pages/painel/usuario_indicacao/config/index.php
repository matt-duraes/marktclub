<?php

use App\Classes\UsuarioIndicacao\Ordem;
use App\Classes\UsuarioIndicacao\Status;

$Painel = new PainelConfig\Index('usuario_indicacao', new Ordem());

$Painel
    ->campo('nome', 'Nome', 'normal')
    ->campo('email', 'E-mail', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
