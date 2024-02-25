<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')->null()
    ->int('id_votacao_dado')->tamanho(9)->relacionado(TABELA_VOTACAO_DADO, 'id')
    ->int('id_votacao_pergunta')->tamanho(9)->relacionado(TABELA_VOTACAO_PERGUNTA, 'id')
    ->int('id_votacao_resposta')->tamanho(9)->relacionado(TABELA_VOTACAO_RESPOSTA, 'id')
    ->varchar('resposta_outro')->tamanho(250)->null()
    ->varchar('voto_livre')->tamanho(250)->null()
    ->text('hash')->null()
    ->dataCriacao()
    ->status();
