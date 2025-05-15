<?php

use App\Classes\EnqueteMercado\Ordem;
use PainelConfig\Index;

$Painel = new Index('enquete_mercado', new Ordem());

$Painel
    ->campo('empresa_nome', 'Empresa', Index::TIPO_GRANDE)
    ->campo('usuario_nome', 'Usuário', Index::TIPO_GRANDE)
    ->dataCriacao();

return $Painel;
