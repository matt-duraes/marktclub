<?php

use App\Classes\Geral\Status;
use Helpers\ApiHelper;

$Painel = new PainelConfig\Add(app: 'banner_login', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem 1', function () use ($Painel) {
        $Painel
            ->imagem(name: 'url_1', diretorio: '420e3bb0-4142-40a0-8fcb-b78c9678c5bd', obrigatorio: true);
    });
    $Painel->fieldset('Imagem 2', function () use ($Painel) {
        $Painel
            ->imagem(name: 'url_2', diretorio: '420e3bb0-4142-40a0-8fcb-b78c9678c5bd');
    });
    $Painel->fieldset('Imagem 3', function () use ($Painel) {
        $Painel
            ->imagem(name: 'url_3', diretorio: '420e3bb0-4142-40a0-8fcb-b78c9678c5bd');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título dos banners')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status'
            );
        ;
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $empresa = (new ApiHelper(token: true))
        ->json(['titulo' => 'Escolha uma empresa'])
        ->get('/comercial-empresa/select')
        ->array()['dado'] ?? [];

    $Painel->coluna(callback: function () use ($Painel, $empresa) {
        $Painel->fieldsetCheckbox(
            titulo: 'Empresas',
            callback: function () use ($Painel, $empresa) {
                foreach ($empresa as $id => $nome) {
                    $Painel->checkbox(name: 'empresa[]', label: $nome ?? 'sem nome fantasia', value: $id);
                }
            },
            todos: 'Marcar todas as empresas',
            mais: true
        );
    });
    $Painel->hidden(name: 'padrao');
});

$Painel->js('painel_banner_login_add');

return $Painel;
