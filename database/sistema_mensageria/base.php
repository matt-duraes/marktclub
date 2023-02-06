<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('tipo')->tamanho(100)
    ->json('payload')
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_enviar_apos')->null()
    ->datetime('data_envio')->null()
    ->int('quantidade_envio')->null()
    ->int('status_resposta')->tamanho(3)->null()
    ->varchar('envio_metodo')->tamanho('6')
    ->varchar('envio_uri')->tamanho(250)->null()
    ->int('envio_api')->tamanho(1)->null()
    ->varchar('envio_scope')->tamanho(100)->null()
    ->status()->null();
