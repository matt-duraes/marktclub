<?php

use App\Classes\EnqueteSatisfacao\Ordem;
use App\Classes\EnqueteSatisfacao\Status;

$Painel = new PainelConfig\Index('enquete_satisfacao', new Ordem());

$Painel
    ->campo('parceiro.nome', 'Parceiro', 'normal')
    ->campo('usuario.nome', 'Usuário', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;