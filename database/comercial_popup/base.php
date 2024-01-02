<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->text('titulo_painel')->null()
    ->json('usuario_tipo')->null()
    ->slug('slug', 'titulo')->tamanho(256)
    ->varchar('imagem')->tamanho(256)->null()
    ->varchar('titulo')->tamanho(256)
    ->longtext('texto')->null()
    ->longtext('regulamento')->null()
    ->date('data_inicio')->null()
    ->date('data_final')->null()
    ->varchar('atualizar_dado')->tamanho(256)->null()
    ->varchar('botao_texto')->tamanho(256)->null()
    ->varchar('botao_link')->tamanho(256)->null()
    ->int('botao_target')->tamanho(3)->null()
    ->int('ordem')->tamanho(4)->padrao(9999)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
