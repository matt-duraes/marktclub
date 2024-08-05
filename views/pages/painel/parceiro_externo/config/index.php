<?php

use App\Classes\Parceiro\Externo\Ordem;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use PainelConfig\Index;

$Painel = new Index('parceiro_externo', new Ordem());

$Painel
    ->campo('titulo_interno', 'Parceiro', Index::TIPO_GRANDE)
    ->campo('tipo_indicador', 'Indicador', Index::TIPO_GRANDE)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

$Painel->replace('tipo_indicador', (new Indicador())->select());

return $Painel;
