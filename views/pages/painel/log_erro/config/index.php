<?php

use PainelConfig\Index;
use System\Classes\LogErro\Ordem;
use System\Classes\LogErro\Status;

$Painel = new Index('log_erro', new Ordem());

$Painel
    ->campo('mensagem', 'Mensagem', Index::TIPO_GRANDE)
    ->campo('quantidade', 'Quantidade', Index::TIPO_PEQUENO)
    ->campo('status_http', 'HTTP', Index::TIPO_PEQUENO)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
