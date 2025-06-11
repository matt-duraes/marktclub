<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;

$empresa = (new ApiHelper(token: true))->get('/comercial-empresa/select')->array()['dado'] ?? [];

$Painel = new PainelConfig\Add(app: 'view_pagina', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título', placeholder: 'Digite um título')
            ->uri(name: 'url', label: 'URL', placeholder: 'Digite a url do clube')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha um status'));
    });
});

$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome ?? '', value: $id);
            }
        },
        todos: 'Marcar todas as empresas',
        mais: true
    );
});

return $Painel;
