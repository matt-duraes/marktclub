<?php

use App\Classes\UsuarioLead\Ordem;
use App\Classes\UsuarioLead\Status;

$Painel = new PainelConfig\Index('usuario_lead', new Ordem());

return $Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('cpf', 'CPF', 'pequeno', 'cpf')
    ->campo('email', 'E-mail', 'normal', 'email')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
