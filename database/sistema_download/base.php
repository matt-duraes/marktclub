<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_equipe')->tamanho(9)
    ->varchar('arquivo')->tamanho(250)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
