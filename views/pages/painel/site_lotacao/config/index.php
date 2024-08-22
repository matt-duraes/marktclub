<?php

use PainelConfig\Index;
use App\Classes\Geral\Status;

$Painel = new Index('site_lotacao');

$Painel
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL, permissao: 'site_lotacao_empresa')
    ->campo('titulo', 'Titulo', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
