<?php

use App\Classes\Geral\Status;
use App\Classes\ComunicacaoPublicidade\Tipo;
use App\Classes\ComunicacaoPublicidade\Ordem;

$Painel = new PainelConfig\Index('comunicacao-publicidade', new Ordem());
$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('parceiro.titulo', 'Parceiro', 'normal')
    ->campo('data_inicio', 'Data de início', 'pequeno')
    ->campo('data_final', 'Data final', 'pequeno')
    ->campo('tipo', 'Tipo', 'pequeno')
    ->campo('publicado', 'Publicado', 'pequeno')
    ->status('status', 'Status', new Status());

$Painel->replace('publicado', ['sim' => 'Sim', 'nao' => 'Não']);
$Painel->replace('tipo', (new Tipo())->select());

return $Painel;
