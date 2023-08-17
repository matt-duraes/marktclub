<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->null()->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('operadora')->tamanho(1)
    ->int('tipo')->tamanho(1)
    ->int('parcela')->tamanho(3)
    ->dinheiro('valor_parcela')
    ->dinheiro('valor_total')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
