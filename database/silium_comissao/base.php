<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->varchar('parceiro')->tamanho(100)
    ->float('valor_compra')
    ->float('comissao_usuario')
    ->int('pontuacao')->tamanho(9)
    ->date('data_compra')
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
