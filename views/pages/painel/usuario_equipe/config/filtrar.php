<?php

use App\Classes\UsuarioEquipe\Helper;

$Painel = new PainelConfig\Filtrar('usuario_equipe');

$status = ['ativo' => 'Ativo', 'inativo' => 'Inativo'];

$Painel
    ->select(name: 'empresa', titulo: 'Empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite o CPF')
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $status);

$Painel->replace('status', $status);
return $Painel;
