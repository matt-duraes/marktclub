<?php

use App\Classes\UsuarioCliente\Ordem;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;

$Painel = new PainelConfig\Index('usuario_cliente', new Ordem());
$Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('empresa->nome_fantasia', 'Empresa', 'pequeno', permissao: Helper::PERMISSAO_EMPRESA)
    ->campo('cpf', 'CPF', 'pequeno', formatar: 'cpf')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
