<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->cod()
    ->int('empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')->replace('id_admin_empresa')
    ->int('usuario')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')->replace('id_usuario_cliente')
    ->int('vinculo')->tamanho(9)->relacionado(TABELA_PARCEIRO_LOJA, 'id')->replace('id_parceiro_loja')
    ->varchar('modelo')->tamanho(100)->null()
    ->varchar('versao')->tamanho(100)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
