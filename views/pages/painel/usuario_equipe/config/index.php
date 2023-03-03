<?php

use App\Classes\UsuarioEquipe\Ordem;
use App\Classes\UsuarioEquipe\Helper;
use App\Classes\UsuarioEquipe\Status;

$Painel = new PainelConfig\Index('usuario_equipe', new Ordem());

return $Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('empresa->nome_fantasia', 'Empresa', 'normal', permissao: Helper::PERMISSAO_EMPRESA)
    ->campo('cpf', 'CPF', 'pequeno', formatar: 'cpf')
    ->campo('email', 'E-mail', 'normal')
    ->status('status', 'Status', new Status());
