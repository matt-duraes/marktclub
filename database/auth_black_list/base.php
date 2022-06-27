<?php

return (new \DataBase\DataBase())
    ->id()
    ->text('id_token')
    ->dataCriacao()
    ->datetime('data_vencimento');
