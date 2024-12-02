<?php

use PainelConfig\Index;
use App\Classes\PainelConfiguracao\Ordem;

$Painel = new Index('painel_config', new Ordem());

$Painel
    ->campo('titulo', 'Título Interno', Index::TIPO_NORMAL)
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->dataAtualizacao();

return $Painel;
