<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoArquivo\Ordem;
use App\Classes\PublicacaoArquivo\Tipo;
use Modules\Botao;
use PainelConfig\Index;

$Painel = new Index('publicacao_arquivo', new Ordem());
$Painel
    ->campo('titulo', 'Título', Index::TIPO_GRANDE)
    ->campo('tipo', 'Tipo', Index::TIPO_PEQUENO)
    ->campo('permissao_site', 'Disponível no Site', Index::TIPO_PEQUENO)
    ->campo('permissao_restrita', 'Disponível na Área Restrita', Index::TIPO_PEQUENO)
    ->campo('publicado', 'Publicado', Index::TIPO_PEQUENO)
    ->status('status', 'Status', new Status());

$Botao = (new Botao())->select();
$Painel->replace('tipo', (new Tipo())->select());
$Painel->replace('permissao_site', $Botao);
$Painel->replace('permissao_restrita', $Botao);
$Painel->replace('publicado', $Botao);

return $Painel;
