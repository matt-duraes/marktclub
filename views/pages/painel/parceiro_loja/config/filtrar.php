<?php

use App\Classes\ParceiroLoja\Helper;
use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Filtrar('parceiro_loja');

$Painel->bloco(function () use ($Painel) {
    $Painel
        ->select(name: 'empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
        ->select(
            name: 'status',
            label: 'Status',
            lista: (new Status())->select('Escolha uma opção')
        );
});

$Painel->replace('status', (new Status())->select());

return $Painel;
