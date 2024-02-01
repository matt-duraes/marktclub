<?php

use App\Classes\UsuarioIndicacao\Helper;
use App\Classes\UsuarioIndicacao\Ordem;
use App\Classes\UsuarioIndicacao\Status;

$Painel = new PainelConfig\Index('usuario_indicacao', new Ordem());

$Painel
    ->campo('empresa', 'Empresa', 'normal', permissao: Helper::PERMISSAO_EMPRESA)
    ->campo('nome', 'Nome do Indicado', 'normal')
    ->campo('email', 'E-mail do Indicado', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
