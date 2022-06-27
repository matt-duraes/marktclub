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

return [
    'html' => [
        [
            [
                'titulo' => 'Imagem',
                'lista' => [
                    'imagem, name:imagem_arquivo, diretorio: e53ae4e0-7b33-4988-99ad-50433a29b544'
                ]
            ],
            [
                'titulo' => 'Dados do cliente',
                'lista' => [
                    'input, name:razao_social, label:Razão Social, obrigatorio:1',
                    'input, name:nome_fantasia, label:Nome Fantasia, obrigatorio:1',
                    'input, name:documento_cnpj, label:CNPJ, mascara:00.000.000/0000-00, numero:1, obrigatorio:1',
                ]
            ],
            [
                'titulo' => 'Dados do responsável',
                'lista' => [
                    'input, name:responsavel_nome, label:Nome completo, obrigatorio:1',
                    'input, name:responsavel_cpf, label:CPF, mascara:000.000.000-00, obrigatorio:1',
                    'input, name:responsavel_email, label:E-mail',
                    'input, name:responsavel_telefone, label:Telefone, mascara:telefone, numero:true'
                ]
            ],
            [
                'titulo' => 'Informações do contrato',
                'lista' => [
                    'select, name:endereco_estado, label: Estado, obrigatorio:1, lista:' . painelSelectConfig($listaEstado),
                    'input, name:endereco_cidade, label: Cidade, obrigatorio:1',
                    'painelCheckbox, titulo:Itens contratados' => $inputItem
                ]
            ]
        ],
        [
            [
                'titulo' => 'Tags',
                'lista' => [
                    'painelCheckbox, todos:Marcar todas as tags, mais:1, margin:' => $inputTag
                ]
            ]
        ],
    ],
];
