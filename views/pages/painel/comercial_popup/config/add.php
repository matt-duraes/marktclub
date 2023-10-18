<?php

use App\Classes\ComercialPopup\BotaoTarget;
use App\Classes\ComercialPopup\Status;

$Painel = new PainelConfig\Add(app: 'comercial_popup', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados Principais', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->data(name: 'data_inicio', label: 'Data de Início')
            ->data(name: 'data_final', label: 'Data Final')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status'
            );
    });
    $Painel->fieldset('Dados do Link', function () use ($Painel) {
        $Painel
            ->input(name: 'botao_texto', label: 'Texto do Botão')
            ->url(name: 'botao_link', label: 'Link do Botão')
            ->select(
                name: 'botao_target',
                lista: (new BotaoTarget())->select('Escolha um tipo'),
                label: 'Tipo de Link'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Conteúdo e Mídia', function () use ($Painel) {
        $Painel
            ->imagem('imagem', LINK_ARQUIVO_PUBLICO)
            ->editor(name: 'texto', label: 'Texto')
            ->editor(name: 'regulamento', label: 'Regulamento');
    });
});

return $Painel;
