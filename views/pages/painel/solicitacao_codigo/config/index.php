<?php

use App\Classes\SolicitacaoCodigo\Ordem;
use App\Classes\SolicitacaoCodigo\Status;
use PainelConfig\Index;

$Painel = new Index('solicitacao_codigo', new Ordem());

$Painel
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL, permissao: 'solicitacao_codigo_empresa')
    ->campo('parceiro->nome', 'Parceiro', Index::TIPO_NORMAL)
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('codigo', 'Código', Index::TIPO_PEQUENO)
    ->campo('data_emissao', 'Data Emissão', Index::TIPO_PEQUENO, Index::FORMATAR_DATAHORA)
    ->campo('data_vencimento', 'Data Vencimento', Index::TIPO_PEQUENO, Index::FORMATAR_DATA)
    ->status('status', 'Status', new Status());

return $Painel;
