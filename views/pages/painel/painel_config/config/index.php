<?php

use App\Classes\PainelConfiguracoes\Ordem;
use PainelConfig\Index;

$Painel = new Index('painel_config', new Ordem());

$Painel
    ->campo('titulo', 'Título Interno', Index::TIPO_NORMAL)
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->dataAtualizacao();

return $Painel;
