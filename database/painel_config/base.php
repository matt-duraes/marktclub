<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->text('permissao')
    ->text('configuracao')->null()
    ->longtext('campo_obrigatorio')->null()
    ->longtext('campo_permitido')->null()
    ->dataCriacao()
    ->dataAtualizacao();
