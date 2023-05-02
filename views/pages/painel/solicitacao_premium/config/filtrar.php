<?php

use Helpers\ListaHelper;
use App\Classes\SolicitacaoPremium\Helper;

$Painel = new PainelConfig\Filtrar('solicitacao_premium');

$Painel
    ->select(
        name: 'empresa',
        label: 'Empresa',
        lista: 'empresa',
        permissao: Helper::PERMISSAO_EMPRESA
    )
    ->select(
        name: 'data',
        titulo: 'Data',
        label: 'Data',
        lista: (new ListaHelper())->add('', 'Escolha uma data')->add(lista: dataListarMesAno(hoje(), '2022-12-01'))->r(),
    );

return $Painel;
