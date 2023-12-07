<?php

use App\Classes\ComunicacaoLogin\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('comunicacao_login', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', 'normal')
    ->status('status', 'Status', new Status());

$Painel->js('painel_comunicacao_login_index');

return $Painel;
