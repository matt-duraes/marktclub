<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_upload_grupo')->tamanho(9)->relacionado('upload_grupo', 'id')->null()
    ->int('id_usuario_equipe')->tamanho(9)->relacionado('usuario_equipe', 'id')
    ->varchar('nome')->tamanho(150)
    ->char('arquivo')->tamanho(36)
    ->varchar('extensao')->tamanho(4)
    ->varchar('tamanho')->tamanho(20)->null()
    ->varchar('largura')->tamanho(5)->null()
    ->varchar('altura')->tamanho(5)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->varchar('privado')->tamanho(20)->null()
    ->status();
