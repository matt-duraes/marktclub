<?php

use App\Classes\SolicitacaoAutomovel\Ordem;
use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Index('solicitacao_automovel', new Ordem());
return $Painel
    ->campo('endereco_estado', 'Estado', 'normal')
    ->campo('endereco_cidade', 'Cidade', 'normal')
    ->campo('montadora', 'Montadora', 'normal')
    ->campo('modelo', 'Modelo', 'normal')
    ->campo('versao', 'Versão', 'normal')
    ->campo('cor', 'Cor', 'normal')
    ->campo('mensagem', 'Mensagem', 'grande')
    ->status('status', 'Status', new Status());
