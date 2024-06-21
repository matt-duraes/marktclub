<?php

use PainelConfig\Index;
use App\Classes\Silium\OrdemSaque;
use App\Classes\Silium\StatusSaque;

$Painel = new Index('silium_saque', new OrdemSaque());

$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new StatusSaque());

return $Painel;
