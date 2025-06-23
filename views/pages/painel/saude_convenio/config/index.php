<?php

use App\Classes\Geral\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('saude_convenio', new Ordem(tabela: TABELA_SAUDE_CONVENIO));

return $Painel
    ->campo('titulo', 'Plano', 'grande')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
