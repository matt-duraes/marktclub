<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->dataCriacao();
