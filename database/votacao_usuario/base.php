<?php

return (new \DataBase\DataBase())
    ->int('id_votacao_dado')->tamanho(9)->relacionado(TABELA_VOTACAO_DADO, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->varchar('nome')->tamanho(250)
    ->cpf('cpf')
    ->int('ordem')->tamanho(5);
