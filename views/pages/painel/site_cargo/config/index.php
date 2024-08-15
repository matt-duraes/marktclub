<?php

use App\Classes\Geral\Status;
use PainelConfig\Index;

$Painel = new Index('site_cargo');

$Painel
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL, permissao: 'site_cargo_empresa')
    ->campo('titulo', 'Titulo', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
