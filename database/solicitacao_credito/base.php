<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->char('codigo')->tamanho(36)
    ->int('empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('operadora')
    ->int('tipo')
    ->varchar('valor')->tamanho(32)
    ->int('parcelas')->tamanho(3)
    ->varchar('valor_parcelas')->tamanho(32)
    ->text('observacao')->null()
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
