<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_automovel_modelo')->relacionado(TABELA_AUTOMOVEL_MODELO, 'id')
    ->varchar('titulo')->tamanho(250)
    ->text('descricao')->null()
    ->dinheiro('valor_de')->null()
    ->dinheiro('valor_por')
    ->varchar('cor')->tamanho(100)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
