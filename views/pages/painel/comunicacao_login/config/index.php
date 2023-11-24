<?php

use App\Classes\ComunicacaoLogin\Ordem;

$Painel = new PainelConfig\Index('comunicacao_login', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', 'normal');

$Painel->js('painel_comunicacao_login_index');

return $Painel;
