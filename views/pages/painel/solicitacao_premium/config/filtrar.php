<?php

use App\Classes\SolicitacaoPremium\Helper;

$Painel = new PainelConfig\Filtrar('solicitacao_premium');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: Helper::PERMISSAO_EMPRESA
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_de', titulo: 'Data inicial', label: 'Data inicial', placeholder: 'Data inicial')
            ->data(name: 'data_ate', titulo: 'Data final', label: 'Data final', placeholder: 'Data final');
    });

return $Painel;
