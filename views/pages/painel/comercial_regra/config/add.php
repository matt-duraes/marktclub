<?php

use Helpers\ApiHelper;

$Painel = new PainelConfig\Add(app: 'comercial-regra', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Título', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título da regra');
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('', function () use ($Painel) {
        $Painel->editorBalao(
            name: 'texto',
            label: 'Texto',
            placeholder: 'Digite seu texto',
            obrigatorio: true,
            // @codingStandardsIgnoreStart
            bar: 'bold,italic,underline,Strikethrough,fwDestaque,|,fontColor,|,alignment,|,link,removeFormat,|,insertTable,fwImagem,fwArquivo,mediaEmbed,|,horizontalLine,FwObservacao,|,numberedList,bulletedList',
            // @codingStandardsIgnoreEnd
            barBalao: 'bold,italic,underline,Strikethrough,fwDestaque,|,fontColor,|,link,removeFormat'
        );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Empresas', function () use ($Painel) {
        $Painel->fieldsetCheckbox(todos: 'Selecionar todos', mais: true, callback: function () use ($Painel) {
            $empresa = (new ApiHelper(token: true))->get('/comercial-empresa/select')->object()->dado ?? [];
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome, value: $id);
            }
        });
    });
});

return $Painel;
