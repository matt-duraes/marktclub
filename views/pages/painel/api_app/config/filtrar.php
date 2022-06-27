<?php

$Empresa = new Painel\AdminEmpresa\Models\AdminEmpresaModel();
$listaEmpresa = $Empresa->pegarEmpresaParaSelect('Escolha uma opção');

$listaStatus = [
    '' => 'Escolha uma opção',
    1 => 'Ativo',
    2 => 'Inativo'
];

return [
    'input' => [
        'input, name:nome, label:Nome, placeholder:Digite o nome do app',
        'select, name:empresa, label: Empresa, lista: ' . painelSelectConfig($listaEmpresa),
        'select, name:status, label: Status, lista: ' . painelSelectConfig($listaStatus)
    ],
    'nome' => [
        'nome' => 'Nome',
        'empresa' => 'Empres',
        'status' => 'Status'
    ],
    'valor' => [
        'empresa' => $listaEmpresa,
        'status' => $listaStatus
    ]
];
