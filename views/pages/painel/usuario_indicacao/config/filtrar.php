<?php

$Painel = new PainelConfig\Filtrar('usuario_indicacao');
$status = [
    'indicado'  => 'Indicado',
    'ativado'   => 'Ativado',
    'bloqueado' => 'Bloqueado'
];

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite o CPF')
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $status);

$Painel->replace('status', $status);

return $Painel;
