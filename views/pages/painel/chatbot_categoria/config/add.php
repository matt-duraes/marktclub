<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('chatbot_categoria');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel
        ->input(name: "categoria", label: "Categoria")
        ->select(
            name: 'status',
            label: 'Status',
            lista: (new Status())->select('Escolha uma opção'),
            obrigatorio: true
        );
});

return $Painel;
