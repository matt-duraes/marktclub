<?php

use App\Classes\ParceiroIndicacao\Ordem;
use App\Classes\ParceiroIndicacao\Status;

$Painel = new PainelConfig\Index('parceiro_indicacao', new Ordem());

$Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('telefone', 'Telefone', 'pequeno', 'telefone')
    ->campo('email', 'E-mail', 'normal')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
