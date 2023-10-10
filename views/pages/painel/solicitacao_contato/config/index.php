<?php

use App\Classes\SolicitacaoContato\Ordem;
use App\Classes\SolicitacaoContato\Status;

$Painel = new PainelConfig\Index('solicitacao_contato', new Ordem());

$Painel
    ->campo('empresa.nome', 'Empresa', 'normal', permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA)
    ->campo('nome', 'Nome', 'normal')
    ->campo('email', 'E-mail', 'normal')
    ->campo('telefone', 'Telefone', 'pequeno', 'telefone')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
