<?php

use App\Classes\ParceiroLoja\Helper;
use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Filtrar('parceiro_loja');

$Painel
    ->select(name: 'empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->input(name: 'titulo', titulo: 'Título', label: 'Título', placeholder: 'Digite um título')
    ->select(
        name: 'status',
        titulo: 'Status',
        label: 'Status',
        lista: (new Status())->select('Escolha uma opção')
    );

$Painel->replace('status', (new Status())->select());

return $Painel;
