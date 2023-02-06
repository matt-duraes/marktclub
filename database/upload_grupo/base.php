<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_upload_grupo')->relacionado(TABELA_UPLOAD_GRUPO, 'id')->null()
    ->text('id_usuario_equipe')->null()
    ->varchar('nome')->tamanho(100)
    ->text('extensao')->null()
    ->varchar('diretorio')->tamanho(100)->null()
    ->varchar('local')->tamanho(20)->null()
    ->varchar('privado')->tamanho(20)->null();
