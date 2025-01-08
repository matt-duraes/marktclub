<?php

use App\Classes\Galapagos\Lead\Helper;
use App\Classes\Galapagos\Lead\Status;

$Painel = new PainelConfig\Filtrar('galapagos_lead');

$status = (new Status())->select('Escolha um status');

$Painel
    ->select(
        name: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        lista: 'empresa',
        permissao: Helper::PERMISSAO_EMPRESA
    )
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->telefone(name: 'celular', titulo: 'Celular', label: 'Celular', placeholder: 'Digite um celular')
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $status);

$Painel->replace('status', $status);
return $Painel;
