<?php

use Helpers\ApiHelper;
use Modules\EnderecoEstado;
use App\Classes\Geral\Status;

$empresa = (new ApiHelper(token: true))->get('/comercial-empresa/select')->array()['dado'] ?? [];

$Painel = new PainelConfig\Add(app: 'saude_convenio', acao: $acao ?? '');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Logo', function () use ($Painel) {
        $Painel->imagem(name: 'arquivo_imagem', diretorio: '78172eda-8afa-467d-9ee3-7947cb6674d4');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Título', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                obrigatorio: true,
                contador: 200
            )
            ->uri(name: 'url', label: 'URI do plano', placeholder: 'URI do plano', livre: true)
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um estado'),
                label: 'Status',
                placeholder: 'Selecione o status'
            );
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

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Estados',
        callback: function () use ($Painel) {
            foreach ((new EnderecoEstado())->select() as $id => $nome) {
                $Painel->checkbox(name: 'endereco_estado[]', label: $nome, value: $id);
            }
        },
        todos: 'Marcar todos os estados',
        mais: true
    );
});

return $Painel;
