<?php

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoDeclaracao\Ordem;

$Painel = new PainelConfig\Index('solicitacao_declaracao', new Ordem());

$Painel
    ->campo('empresa.nome', 'Empresa', 'normal', permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA)
    ->campo('usuario.nome', 'Usuário', 'normal')
    ->campo('parceiro.nome', 'Parceiro', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
