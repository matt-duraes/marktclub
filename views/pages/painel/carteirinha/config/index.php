<?php

use App\Classes\Carteirinha\Ordem;
use App\Classes\Carteirinha\Status;

$Painel = new PainelConfig\Index('carteirinha', new Ordem());

$Painel
    ->campo('empresa.nome', 'Empresa', 'normal', permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
