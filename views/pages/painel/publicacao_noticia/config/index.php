<?php

use App\Classes\StatusGeral\Status;
use App\Classes\PublicacaoNoticia\Ordem;

$Painel = new PainelConfig\Index('solicitacao_salavip', new Ordem());
return $Painel
    ->campo('titulo_pequeno', 'Título', 'grande')
    ->campo('data', 'Publicada em', 'pequeno')
    ->status('status', 'Status', new Status());
