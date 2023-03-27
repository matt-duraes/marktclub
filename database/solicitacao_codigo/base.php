<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('uuid')->tamanho(36)
    ->int('id_admin_empresa')->tamanho(9)->null()
    ->int('id_usuario_cliente')->tamanho(9)->null()
    ->int('id_parceiro_loja')->tamanho(9)
    ->varchar('codigo')->tamanho(20)
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_emissao')->null()
    ->date('data_vencimento')->null()
    ->status();
