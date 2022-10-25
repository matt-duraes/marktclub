<?php

use App\Classes\UsuarioCliente\Ordem;
use App\Classes\UsuarioCliente\Status;

$Painel = new PainelConfig\Index('usuario_cliente', new Ordem);
$Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('cpf', 'CPF', 'pequeno', formatar: 'cpf')
    ->campo('email', 'E-mail', 'normal')
    ->campo('tipo', 'Tipo', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
