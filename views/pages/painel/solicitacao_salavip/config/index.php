<?php

use App\Classes\SolicitacaoSalavip\Ordem;

$Painel = new PainelConfig\Index('solicitacao_salavip', new Ordem());
return $Painel
    ->campo('empresa', 'Empresa', 'grande')
    ->campo('codigo', 'Código', 'pequeno')
    ->campo('data', 'Valida em', 'pequeno');
