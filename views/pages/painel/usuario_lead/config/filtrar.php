<?php

use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioLead\Status;

$Painel = new PainelConfig\Filtrar('usuario_lead');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite um CPF')
    ->numero(name: 'matricula', titulo: 'Matrícula', label: 'Matrícula', placeholder: 'Digite uma matrícula')
    ->numero(name: 'siape', titulo: 'SIAPE', label: 'SIAPE', placeholder: 'Digite um SIAPE')
    ->bloco(function () use ($Painel) {
    $Painel
        ->select(name: 'origem', titulo: 'Origem', label: 'Origem', lista: (new Origem())->select())
        ->select(name: 'status', titulo: 'Status', label: 'Status', lista: (new Status())->select());
    });

    $Painel->replace('status', (new Status())->select());

return $Painel;