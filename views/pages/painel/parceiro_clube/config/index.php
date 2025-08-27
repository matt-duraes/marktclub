<?php

use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use Modules\EnderecoEstado;
use PainelConfig\Index;

$Painel = new Index('parceiro_clube', new Ordem());

$Painel
    ->campo('titulo_interno', 'Parceiro', Index::TIPO_NORMAL)
    ->campo('razao_social', 'Razão Social', Index::TIPO_NORMAL)
    ->campo('desconto', 'Descontos', Index::TIPO_PEQUENO)
    ->campo('endereco_estado', 'Estados', Index::TIPO_PEQUENO)
    ->status('status', 'Status', new Status());

$Painel->replace('endereco_estado', (new EnderecoEstado())->select());

return $Painel;
