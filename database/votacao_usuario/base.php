<?php

return (new \DataBase\DataBase())
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('id_votacao_dado')->tamanho(9)->relacionado(TABELA_VOTACAO_DADO, 'id')
    ->int('ordem')->tamanho(5);
