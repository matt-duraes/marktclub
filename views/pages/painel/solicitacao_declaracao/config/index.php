<?php

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoDeclaracao\Ordem;

$Painel = new PainelConfig\Index('solicitacao_declaracao', new Ordem());
$Painel
    ->campo('parceiro', 'Empresa', 'grande')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
