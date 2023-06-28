<?php

use App\Classes\UsuarioCliente\Ordem;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;

$Painel = new PainelConfig\Index('usuario_cliente', new Ordem());
$Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('empresa->nome_fantasia', 'Empresa', 'pequeno', permissao: Helper::PERMISSAO_EMPRESA)
    ->campo('tipo', 'Tipo', 'pequeno')
    ->campo('cpf', 'CPF', 'pequeno', formatar: 'cpf')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('tipo', new TipoUsuario());
return $Painel;
