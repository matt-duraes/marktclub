<?php

use Helpers\ListaHelper;

$Tag = new \Painel\DataPolicy\Models\DataPolicy;
$Item = new \Painel\DocumentoItem\Models\DocumentoItemModel;

$listaTag = $Tag->pegarSelect();
$inputTag = [];
foreach ($listaTag as $ind => $val) {
    $inputTag[] = 'checkbox, name:tag[], label:' . $val . ', value:' . $ind;
}

$listaItem = $Item->pegarSelect();
$inputItem = [];
foreach ($listaItem as $ind => $val) {
    $inputItem[] = 'checkbox, name:item[], label: ' . $val . ', value:' . $ind;
}

$listaEstado = (new ListaHelper)->estado()->r();
$listaStatus = [1 => 'Liberado', 2 => 'Inativo', 3 => 'Em prospecção'];

return [
    'input' => [
        'input, name:nome, label:Nome, placeholder:Digite a razão social ou nome fantasia',
        'input, name:cnpj, label:CNPJ, placeholder:Digite um CNPJ, mascara:00.000.000/0000-00, numero:1',
        'painelCheckbox, titulo:Tags, todos:Marcar todas as tags, mais:1' => $inputTag,
        'painelCheckbox, titulo:Itens contratados, todos: Marcar todos os itens, mais:1' => $inputItem,
        'select, name:estado, label: Escolha um estado, lista: ' . painelSelectConfig($listaEstado),
        'input, name:cidade, label:Cidade, placeholder:Digite o nome de uma cidade',
        'select, name:status, label: Status, lista: ' . painelSelectConfig($listaStatus)
    ],
    'nome' => [
        'nome' => 'Nome',
        'cnpj' => 'CNPJ',
        'tag' => 'Tags',
        'item' => 'Itens',
        'estado' => 'Estado',
        'cidade' => 'Cidade',
        'status' => 'Status'
    ],
    'valor' => [
        'estado' => $listaEstado,
        'status' => $listaStatus
    ]
];
