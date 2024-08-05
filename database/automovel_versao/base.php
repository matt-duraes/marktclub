<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_automovel_modelo')->relacionado(TABELA_AUTOMOVEL_MODELO, 'id')
    ->varchar('titulo')->tamanho(250)
    ->text('descricao')->null()
    ->dinheiro('valor_de')->null()
    ->dinheiro('valor_por')
    ->varchar('cor')->tamanho(100)->null()
    ->varchar('imagem')->tamanho(256)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
