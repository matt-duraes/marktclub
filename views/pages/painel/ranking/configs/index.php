<?php

use PainelConfig\Index;

$Painel = new Index('ranking');

$Painel
    ->campo('empresa', 'Empresa', Index::TIPO_GRANDE)
    ->campo('quantidade', 'Quantidade de Indicações', Index::TIPO_GRANDE);

return $Painel;
