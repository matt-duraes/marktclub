<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ParceiroEasylive\Tipo;

$empresa = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add('parceiro_easylive');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem('imagem', '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->select(name: 'tipo', label: 'Tipo', lista: (new Tipo())->select('Escolha um tipo'))
            ->data(name: 'data_validade', label: 'Data de validade', placeholder: 'Data de validade')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));
    });
});

$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        todos: 'Marcar todas as empresas',
        mais: true,
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome, value: $id);
            }
        }
    );
});
return $Painel;
