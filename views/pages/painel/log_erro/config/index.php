<?php

use App\Classes\LogErro\Status;

$Painel = new PainelConfig\Index('log_erro');

return $Painel
    ->campo('mensagem', 'mensagem', 'grande')
    ->campo('quantidade', 'Quantidade', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
