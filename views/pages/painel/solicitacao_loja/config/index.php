<?php

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;
use PainelConfig\Index;

$Painel = new Index('solicitacao_loja', new Ordem());

$Painel
    ->campo('empresa', 'Empresa', Index::TIPO_GRANDE, permissao: 'solicitacao_loja_empresa')
    ->campo('usuario_indicacao', 'Usuário', Index::TIPO_GRANDE, permissao: 'solicitacao_loja_empresa')
    ->campo('parceiro', 'Parceiro/Loja', Index::TIPO_GRANDE)
    ->campo('nome_indicacao', 'Nome Indicação', Index::TIPO_NORMAL)
    ->campo('data_prospeccao', 'Data Prospecção', Index::TIPO_PEQUENO, Index::FORMATAR_DATA)
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
