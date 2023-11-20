<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->char('id_api_app')->tamanho(36)->null()
    ->int('id_admin_empresa')->null()
    ->int('id_usuario_cliente')->null()
    ->char('token_acesso')->tamanho(36)->replace('access_token')
    ->status();
