<?php

use Helpers\ListaHelper;
use App\Classes\Comercial\Empresa\ID;

return [
    [
        'id_admin_empresa' => [ID::YOUHUUL, ID::ANAFECARD, ID::CFM],
        'endereco_estado' => ['SP'],
        'endereco_cidade' => [
            'barueri' => 'Barueri',
            'cabreuva' => 'Cabreúva',
            'caieiras' => 'Caieiras',
            'cajamar' => 'Cajamar',
            'campo-limpo-paulista' => 'Campo Limpo Paulista',
            'francisco-morato' => 'Francisco Morato',
            'franco-da-rocha' => 'Franco Da Rocha',
            'itupeva' => 'Itupeva',
            'jarinu' => 'Jarinú',
            'jundiai' => 'Jundiaí',
            'louveira' => 'Louveira',
            'santana-de-parnaiba' => 'Santana de Parnaíba',
            'varzea-paulista' => 'Várzea Paulista',
        ],
        'sequencia' => [
            'acomodacao' => 'Acomodação',
            'plano' => 'Plano',
            'simulacao' => 'Simulação',
            'resultado' => 'Resultado'
        ],
        'item' => [
            'acomodacao' => [
                'enfermagem' => 'Enfermagem',
                'apartamento' => 'Apartamento'
            ],
            'plano' => [
                'enfermagem' => [
                    'flex-ideal' => 'Flex Ideal',
                    'classico-ideal' => 'Clássico Ideal'
                ],
                'apartamento' => [
                    'flex-plus' => 'Flex Plus',
                    'classico-plus' => 'Clássico Plus'
                ]
            ]
        ],
        'simulacao' => [
            'plano'
        ],
        'titulo' => 'Plano SP Cidade',
        'arquivo_imagem' => uuid(),
        'url' => 'plano-sp-cidade',
        'status' => 1
    ],
    [
        'id_admin_empresa' => [ID::YOUHUUL, ID::ANAFECARD, ID::CFM],
        'endereco_estado' => ['RN'],
        'endereco_cidade' => [],
        'sequencia' => [
            'acomodacao' => 'Acomodação',
            'plano' => 'Plano',
            'simulacao' => 'Simulação',
            'resultado' => 'Resultado'
        ],
        'item' => [
            'acomodacao' => [
                'enfermagem' => 'Enfermagem',
                'apartamento' => 'Apartamento'
            ],
            'plano' => [
                'enfermagem' => [
                    'flex-ideal' => 'Flex Ideal',
                    'classico-ideal' => 'Clássico Ideal'
                ],
                'apartamento' => [
                    'flex-plus' => 'Flex Plus',
                    'classico-plus' => 'Clássico Plus'
                ]
            ]
        ],
        'simulacao' => [
            'plano'
        ],
        'titulo' => 'Plano RN',
        'arquivo_imagem' => uuid(),
        'url' => 'plano-rn',
        'status' => 1
    ],
    [
        'id_admin_empresa' => [ID::YOUHUUL, ID::ANAFECARD, ID::CFM],
        'endereco_estado' => array_keys((new ListaHelper())->estado()->r()),
        'endereco_cidade' => [],
        'sequencia' => [
            'acomodacao' => 'Acomodação',
            'plano' => 'Plano',
            'simulacao' => 'Simulação',
            'resultado' => 'Resultado'
        ],
        'item' => [
            'acomodacao' => [
                'enfermagem' => 'Enfermagem',
                'apartamento' => 'Apartamento'
            ],
            'plano' => [
                'enfermagem' => [
                    'flex-ideal' => 'Flex Ideal',
                    'classico-ideal' => 'Clássico Ideal'
                ],
                'apartamento' => [
                    'flex-plus' => 'Flex Plus',
                    'classico-plus' => 'Clássico Plus'
                ]
            ]
        ],
        'simulacao' => [
            'plano'
        ],
        'titulo' => 'Plano Nacional',
        'arquivo_imagem' => uuid(),
        'url' => 'plano-nacional',
        'status' => 1
    ],
    [
        'id_admin_empresa' => [ID::YOUHUUL, ID::ANAFECARD, ID::CFM],
        'endereco_estado' => ['TO', 'DF', 'GO'],
        'endereco_cidade' => [],
        'sequencia' => [
            'estado' => 'Estado',
            'acomodacao' => 'Acomodação',
            'plano' => 'Plano',
            'simulacao' => 'Simulação',
            'resultado' => 'Resultado'
        ],
        'item' => [
            'estado' => [
                'DF' => 'Distrito Federal',
                'GO' => 'Goiás',
                'TO' => 'Tocantins'
            ],
            'acomodacao' => [
                'DF' => [
                    'enfermagem' => 'Enfermagem',
                    'apartamento' => 'Apartamento'
                ],
                'GO' => [
                    'enfermagem' => 'Enfermagem',
                ],
                'TO' => [
                    'enfermagem' => 'Enfermagem',
                    'apartamento' => 'Apartamento'
                ]
            ],
            'plano' => [
                'enfermagem' => [
                    'flex-ideal' => 'Flex Ideal',
                    'classico-ideal' => 'Clássico Ideal'
                ],
                'apartamento' => [
                    'flex-plus' => 'Flex Plus',
                    'classico-plus' => 'Clássico Plus'
                ]
            ]
        ],
        'simulacao' => [
            'estado', 'plano'
        ],
        'titulo' => 'Plano vários estados',
        'arquivo_imagem' => uuid(),
        'url' => 'plano-varios-estados',
        'status' => 1
    ],
];
