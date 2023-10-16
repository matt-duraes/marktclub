<?php

$Painel = new PainelConfig\Add(app: 'carteirinha', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados Principais', function () use ($Painel) {
        $Painel
            ->editor(name: 'texto', label: 'Texto')
            ->editor(name: 'texto_perdido', label: 'Texto Perdido (Em caso de perda)');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Conteúdo e Mídia', function () use ($Painel) {
        $Painel
            ->imagem(name: 'bg_frente', diretorio: LINK_ARQUIVO_PUBLICO)
            ->imagem(name: 'bg_fundo', diretorio: LINK_ARQUIVO_PUBLICO);
    });
});

return $Painel;
