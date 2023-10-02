<?php

use App\Classes\ComunicacaoContato\Ordem;
use App\Classes\ComunicacaoContato\Status;

$Painel = new PainelConfig\Index('comunicacao_contato', new Ordem());
$Painel
    ->campo('parceiro.nome', 'Parceiro', 'normal')
    ->campo('nome', 'Nome', 'normal')
    ->campo('email', 'E-mail', 'normal')
    ->campo('telefone', 'Telefone', 'pequeno', 'telefone')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
