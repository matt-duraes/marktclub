<?php

use App\Classes\ComunicacaoLogin\Ordem;
use App\Classes\Geral\Status;
use Modules\Botao;

$Painel = new PainelConfig\Index('comunicacao_login', new Ordem());

$Painel
    ->campo('titulo', 'Título', 'normal')
    ->campo('publicado', 'Publicado', 'pequeno')
    ->campo('data_inicio', 'Data Início', 'pequeno', 'data')
    ->campo('data_fim', 'Data Final', 'pequeno', 'data')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

$Painel->replace('publicado', (new Botao())->select());
$Painel->js('painel_comunicacao_login_index');

return $Painel;
