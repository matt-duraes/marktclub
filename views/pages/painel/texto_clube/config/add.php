<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\TextoClube\Tipo;

$Painel = new PainelConfig\Add(app: 'texto_clube', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 200,
                obrigatorio: true
            )
            ->select(name: 'tipo', label: 'Tipo', placeholder: 'Tipo', lista: (new Tipo())->select('Escolha um tipo'))
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Status',
                lista: (new Status())->select('Escolha um status')
            );
    });
    $Painel->fieldset('SEO', function () use ($Painel) {
        $Painel
            ->input(
                name: 'header_titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 65
            )
            ->input(name: 'header_descricao', label: 'Descrição', placeholder: 'Digite uma descrição', contador: 155)
            ->tag(name: 'header_tag', label: 'Tags', placeholder: 'Digite sua tags', tipo: 'texto', espaco: true);
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Texto', function () use ($Painel) {
        $Painel->editorBalao(
            name: 'texto',
            label: 'Texto',
            placeholder: 'Digite seu texto',
            diretorioImagem: '4a813b55-cc1b-4d48-8368-091ea31926b2',
            diretorioArquivo: '4a813b55-cc1b-4d48-8368-091ea31926b2',
            obrigatorio: true,
            // @codingStandardsIgnoreStart
            bar: 'bold,italic,underline,Strikethrough,fwDestaque,|,fontColor,|,alignment,|,link,removeFormat,|,insertTable,fwImagem,fwArquivo,mediaEmbed,|,horizontalLine,FwObservacao,|,numberedList,bulletedList',
            // @codingStandardsIgnoreEnd
            barBalao: 'bold,italic,underline,Strikethrough,fwDestaque,|,fontColor,|,link,removeFormat'
        );
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
});

return $Painel;
