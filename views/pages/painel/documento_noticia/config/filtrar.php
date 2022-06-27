<?php

$Tag = new \Painel\DataPolicy\Models\DataPolicy;
$listaTag = $Tag->pegarSelect();
$inputTag = [];
foreach ($listaTag as $ind => $val) {
    $inputTag[] = 'checkbox, name:tag[], label:' . $val . ', value:' . $ind;
}

$statusLista = [
    '' => 'Escolha uma opção',
    1 => 'Liberado',
    2 => 'Inativo'
];

return [
    'input' => [
        'input, name:titulo, label:Título, placeholder:Digite um título',
        'painelCheckbox, titulo:Tags, todos:Marcar todas as tags, mais:1' => $inputTag,
        'select, name:status, label: Status, lista: ' . painelSelectConfig($statusLista)
    ],
    'nome' => [
        'titulo' => 'Título',
        'status' => 'Status'
    ],
    'valor' => [
        'status' => $statusLista
    ]
];
