<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_saude_convenio')->tamanho(9)->relacionado(TABELA_SAUDE_CONVENIO, 'id')
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_cliente')->tamanho(9)
    ->json('valor_titular')
    ->json('valor_dependente')->null()
    ->json('escolhido')
    ->decimal('valor_total')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
