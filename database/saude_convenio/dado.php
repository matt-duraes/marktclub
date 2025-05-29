<?php

use App\Classes\Comercial\Empresa\ID;

return [
    [
        'id_admin_empresa' => [ID::YOUHUUL, ID::ANAFECARD, ID::CFM],
        'endereco_estado' => ['SP'],
        'endereco_cidade' => [
            'Barueri', 'Cabreúva', 'Caieiras', 'Cajamar', 'Campo Limpo Paulista', 'Francisco Morato',
            'Franco Da Rocha', 'Itupeva', 'Jarinú', 'Jundiaí', 'Louveira', 'Santana de Parnaíba', 'Várzea Paulista',
        ],
        'sequencia' => [
            'acomodacao' => 'Acomodação',
            'plano' => 'Plano',
            'simulacao' => 'Simulação',
            'resultado' => 'Resultado'
        ],
        'titulo' => 'Unimed Jundiaí',
        'arquivo_imagem' => uuid(),
        'url' => 'unimed-jundiai',
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
        'titulo' => 'Unimed Natal',
        'arquivo_imagem' => uuid(),
        'url' => 'unimed-natal',
        'status' => 1
    ],
];
