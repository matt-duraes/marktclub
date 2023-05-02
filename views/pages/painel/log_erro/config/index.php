<?php

use System\Classes\LogErro\Status;

$Painel = new PainelConfig\Index('log_erro');

return $Painel
    ->campo('mensagem', 'mensagem', 'grande')
    ->campo('quantidade', 'Quantidade', 'pequeno')
    ->campo('status_http', 'HTTP', 'pequeno')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());
