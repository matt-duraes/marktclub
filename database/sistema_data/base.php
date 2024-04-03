<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_equipe')->relacionado(TABELA_USUARIO_EQUIPE, 'id', DataBase::NO_ACTION, DataBase::NO_ACTION)
    ->int('id_painel_historico')->relacionado(TABELA_PAINEL_HISTORICO, 'id', DataBase::NO_ACTION, DataBase::NO_ACTION)->null()
    ->char('id_vinculo')->tamanho(36)
    ->varchar('local_principal')->tamanho(100)
    ->varchar('mensagem')->tamanho(150)->null()
    ->dataCriacao();
