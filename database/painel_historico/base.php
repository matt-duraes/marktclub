<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_equipe')->tamanho(9)->relacionado('usuario_equipe', 'id', )
    ->json('id_relacionado')->null()
    ->varchar('app')->tamanho(50)
    ->varchar('acao')->tamanho(30)
    ->text('dado')->null()
    ->text('mensagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status');
