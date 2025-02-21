<?php

use App\Classes\UsuarioLead\Status;
use App\Classes\UsuarioCliente\Origem;

$Painel = new PainelConfig\Filtrar('usuario_lead');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->numero(name: 'siape', titulo: 'SIAPE', label: 'SIAPE', placeholder: 'Digite um SIAPE')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite um CPF')
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(name: 'origem', lista: (new Origem())->select(), titulo: 'Origem', label: 'Origem')
            ->select(name: 'status', lista: (new Status())->select(), titulo: 'Status', label: 'Status');
    });

$Painel->replace('status', (new Status())->select());

return $Painel;
