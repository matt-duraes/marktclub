<?php

use App\Classes\UsuarioIndicacao\Status;

$Painel = new PainelConfig\Filtrar('usuario_indicacao');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite o CPF')
    ->select(
        name: 'status',
        lista: (new Status())->select('Selecione um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
