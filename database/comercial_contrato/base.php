<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_comercial_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->date('data_contrato_inicio')
    ->date('data_contrato_renovacao');
