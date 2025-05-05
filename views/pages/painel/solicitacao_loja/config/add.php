<?php

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use Helpers\ApiHelper;
use PainelConfig\Add;

$Painel = new Add(app: 'solicitacao_loja', acao: $acao);

$parceiro = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um parceiro', 'tipo_loja' => TipoLoja::LOJA, 'status' => Status::PROSPECCAO])
    ->get('/parceiro-loja/select')
    ->array();

$Painel->coluna(callback: function () use ($Painel, $parceiro) {
    $Painel->fieldset('Víncular Parceiro', function () use ($Painel, $parceiro) {
        $Painel
            ->select(
                name: 'parceiro',
                lista: empty($parceiro['dado']) ? ['' => 'Nenhum parceiro encontrado'] : $parceiro['dado'],
                label: 'Parceiro'
            );
    });
    $Painel->fieldset('Prospectar e vincular parceiro', function () use ($Painel) {
        $Painel
            ->input(
                name: 'parceiro_novo',
                label: 'Informe o nome do parceiro',
                placeholder: 'Nome do parceiro',
                ajuda: 'Informe o nome do parceiro que deseja adicionar e vincular a esta indicação'
            );
    });
});

$Painel->js('painel_solicitacao_loja_add');

return $Painel;
