<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('tipo')->tamanho(1)
    ->varchar('titulo')->tamanho(250)
    ->text('texto')
    ->int('voto_unico')->tamanho(1)->null()
    ->int('identificar_usuario')->tamanho(1)->null()
    ->int('salvar_hash')->tamanho(1)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->slug('url', 'titulo')
    ->datetime('data_inicio')
    ->datetime('data_final')
    ->botao('bloqueado')->null()
    ->status();
