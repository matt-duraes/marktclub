<?php

use App\Classes\Automovel\Modelo\Ordem;
use Modules\Botao;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('parceiro_automovel', new Ordem());

$Painel
    ->campo('titulo', 'Título', 'normal')
    ->campo('parceiro->titulo', 'Parceiro', 'normal')
    ->campo('url', 'URL', 'normal')
    ->campo('data_inicio', 'Data Inicío', 'pequeno', 'data')
    ->campo('data_final', 'Data Final', 'pequeno', 'data')
    ->campo('publicado', 'Publicado', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('publicado', new Botao());

return $Painel;
