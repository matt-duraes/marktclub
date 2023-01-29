<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_dono')->tamanho(9)->relacionado('usuario_equipe', 'id')
    ->int('id_usuario_equipe')->tamanho(9)->relacionado('usuario_equipe', 'id')
    ->varchar('titulo')->tamanho(100)
    ->varchar('mensagem')->tamanho(250)
    ->text('link')->null()
    ->varchar('botao')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_vializacao')->null()
    ->status();
