<?php

use App\Classes\Geral\Status;
use App\Classes\UsuarioClienteCodigo\Ordem;
use PainelConfig\Index;

$Painel = new Index('usuario_cliente_codigo', new Ordem());

$Painel
    ->campo('empresa_nome', 'Empresa', Index::TIPO_GRANDE)
    ->campo('subempresa_nome', 'Subempresa', Index::TIPO_GRANDE)
    ->campo('codigo', 'Código', Index::TIPO_PEQUENO)
    ->status('status', 'Status', new Status());

return $Painel;
