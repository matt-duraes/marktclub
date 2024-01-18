<?php

use App\Classes\PainelConfiguracoes\Ordem;

$Painel = new PainelConfig\Index('painel_config', new Ordem());

$Painel
    ->campo('empresa->nome', 'Empresa', 'normal')
    ->dataCriacao()
    ->dataAtualizacao();

return $Painel;
