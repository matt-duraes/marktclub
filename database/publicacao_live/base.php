<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('titulo')->tamanho(250)
    ->varchar('titulo_interno')->tamanho(250)
    ->longtext('texto')->null()
    ->imagem('imagem_site')->null()
    ->imagem('imagem_restrito')->null()
    ->text('link')
    ->botao('permissao_restrita')
    ->botao('permissao_site')
    ->botao('link_restrito')
    ->datetime('data_inicio')
    ->datetime('data_final')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
