<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('chatbot_perguntas');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'pergunta', label: 'Pergunta')
            ->input(name: 'categoria', label: 'Categoria')
            ->editorBalao(name: 'resposta', label: 'Resposta')
            ->select(
                name: 'status',
                label: 'Status',
                lista: (new Status())->select('Escolha uma opção'),
                obrigatorio: true
            );
    });
});

return $Painel;
