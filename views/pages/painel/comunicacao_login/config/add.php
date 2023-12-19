<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add(app: 'comunicacao_login', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem 1', function () use ($Painel) {
        $Painel
            ->imagem(name: 'arquivo_1', diretorio: '420e3bb0-4142-40a0-8fcb-b78c9678c5bd', obrigatorio: true);
    });
    $Painel->fieldset('Imagem 2', function () use ($Painel) {
        $Painel
            ->imagem(name: 'arquivo_2', diretorio: '420e3bb0-4142-40a0-8fcb-b78c9678c5bd');
    });
    $Painel->fieldset('Imagem 3', function () use ($Painel) {
        $Painel
            ->imagem(name: 'arquivo_3', diretorio: '420e3bb0-4142-40a0-8fcb-b78c9678c5bd');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título do banner');
        $Painel
            ->data(name: 'data_inicio', label: 'Data de início', obrigatorio: true)
            ->data(name: 'data_fim', label: 'Data de fim', obrigatorio: true);
        $Painel
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Selecione um status'), obrigatorio: true);
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        todos: 'Marcar todas as empresas',
        mais: true,
        callback: function () use ($Painel) {
            $empresa = (new ApiHelper(token: true))
                ->get('/comercial-empresa/select')
                ->array()['dado'] ?? [];
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome, value: $id);
            }
        }
    );
    $Painel->hidden(name: 'padrao');
});

$Painel->js('painel_comunicacao_login_add');

return $Painel;
