<?php

use App\Classes\AlbumDado\Ordem;
use App\Classes\Geral\Status;
use Modules\Botao;
use PainelConfig\Index;

$Painel = new Index('album_dado', new Ordem());

$Painel
    ->campo('empresa', 'Empresa', Index::TIPO_GRANDE, permissao: 'album_dado_empresa')
    ->campo('equipe', 'Criado por', Index::TIPO_GRANDE)
    ->campo('titulo', 'Titulo do Álbum', Index::TIPO_GRANDE)
    ->campo('permissao_restrita', 'Área Restrita', Index::TIPO_PEQUENO)
    ->campo('permissao_site', 'Público', Index::TIPO_PEQUENO)
    ->campo('data_inicio', 'Data de Publicação', Index::TIPO_NORMAL, Index::FORMATAR_DATA)
    ->campo('data_final', 'Data de Remoção', Index::TIPO_NORMAL, Index::FORMATAR_DATA)
    ->status('status', 'Status', new Status());

$Painel->replace('permissao_restrita', ['1' => 'Sim', '' => 'Não']);
$Painel->replace('permissao_site', ['1' => 'Sim', '' => 'Não']);

return $Painel;
