<?php

use App\Classes\Parceiro\Externo\Ordem;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use PainelConfig\Index;

$Painel = new Index('parceiro_externo', new Ordem());

$Painel
    ->campo('empresa_nome', 'Empresa', Index::TIPO_GRANDE, permissao: 'parceiro_externo_empresa')
    ->campo('equipe_nome', 'Equipe', Index::TIPO_GRANDE, permissao: 'parceiro_externo_equipe')
    ->campo('titulo_interno', 'Parceiro', Index::TIPO_GRANDE)
    ->campo('categoria', 'Categoria', Index::TIPO_PEQUENO)
    ->campo('indicador', 'Indicador', Index::TIPO_PEQUENO)
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('categoria', (new Categoria())->select());
$Painel->replace('indicador', (new Indicador())->select());

return $Painel;
