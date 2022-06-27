<?php

$Empresa = new Painel\AdminEmpresa\Models\AdminEmpresaModel();
$empresa = painelSelectConfig($Empresa->pegarEmpresaParaSelect('Escolha uma empresa'));

return [
    'html' => [
        [
            [
                'titulo' => 'Imagem',
                'lista' => [
                    'imagem, name:imagem_app, diretorio: e53ae4e0-7b33-4988-99ad-50433a29b544'
                ]
            ],
            [
                'titulo' => 'Dados do App',
                'lista' => [
                    'input, name:nome, label:Nome do app, obrigatorio:1',
                    'select, name:audience, label: Qual Audiencia do App?, obrigatorio:1, lista: ' . painelSelectConfig([
                        '' => 'Escolha uma opção',
                        'app' => 'Aplicativo',
                        'clube' => 'Clube',
                        'site' => 'Site'
                    ]),
                    'select, name:tipo, label: Qual tipo do App?, obrigatorio:1, lista: ' . painelSelectConfig([
                        '' => 'Escolha uma opção',
                        1 => 'App para Login',
                        2 => 'App para uso interno'
                    ]),
                    'switch, name:refresh_token, label: O Token pode ser atualizado?',
                ]
            ],
            [
                'titulo' => 'Dados de segurança',
                'lista' => [
                    'select, name:tempo_vida, label: Tempo de vida dos tokens, obrigatorio:1, lista: ' . painelSelectConfig([
                        '' => 'Escolha uma opção',
                        '5' => '5 minutos',
                        '15' => '15 minutos',
                        '30' => '30 minutos',
                        '60' => '1 hora',
                        '1440' => '1 dia',
                        '43800' => '1 Mês',
                        '131400' => '3 Meses',
                        '262800' => '6 Meses',
                        '525600' => '12 Meses',
                    ]),
                    'switch, name:status, label: Liberar App?',
                    'tag, name:redirect_uri, label: URL liberadas, tipo: url'
                ]
            ]
        ],
        [
            [
                'titulo' => 'Scopos',
                'lista' => [
                    'include' => ROOT . '/views/pages/painel/api_app/Views/scope/index.php'
                ]
            ]
        ]
    ],
];
