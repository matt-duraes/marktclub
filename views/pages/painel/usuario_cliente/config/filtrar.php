<?php

use App\Classes\UsuarioCliente\Status;

$Painel = new PainelConfig\Filtrar('usuario_cliente');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite o nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite um CPF')
    ->input(name: 'matricula', titulo: 'Matrícula', label: 'Matrícula', placeholder: 'Digite uma matrícula')
    ->input(name: 'siape', titulo: 'SIAPE', label: 'SIAPE', placeholder: 'Digite um SIAPE')
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_criacao_de', titulo: 'Criado em', label: 'Data de criação de', placeholder: 'Data de criação de')
            ->data(name: 'data_criacao_ate', titulo: 'Criado até', label: 'Data de criação ate', placeholder: 'Data de criação ate');
    })
    ->data(name: 'data_upload', titulo: 'Data de upload', label: 'Data de upload', placeholder: 'Data de upload')
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: (new Status())->select())
    ->switch(name: 'pagamento', titulo: 'Pagamento em aberto', label: 'Apenas pagamento em aberto?');

$Painel->replace('status', (new Status())->select());

return $Painel;
