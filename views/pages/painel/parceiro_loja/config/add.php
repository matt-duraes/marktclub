<?php

use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Add(app: 'parceiro_loja', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem(name: 'imagem', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
    $Painel->fieldset('Dados principais', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título pubico',
                contador: 80,
                obrigatorio: true
            )
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção')
            );
    });
});

return $Painel;
