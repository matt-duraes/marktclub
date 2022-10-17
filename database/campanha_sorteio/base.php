<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_ADMIN_EMPRESA, 'id')
    ->int('id_campanha_popup')->tamanho(9)
    ->varchar('titulo_painel')->tamanho(100)
    ->varchar('titulo')->tamanho(100)
    ->text('texto')
    ->imagem('imagem')
    ->datetime('data_sorteio')->null()
    ->char('hash')->tamanho(36)->null()
    ->int('numero_sorteado')->tamanho(2)
    ->json('usuario_sorteado')->null()
    ->json('lista_usuario')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
