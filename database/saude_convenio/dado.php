<?php

use Helpers\ListaHelper;
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
        'titulo' => 'Plano vários estados',
        'arquivo_imagem' => uuid(),
        'url' => 'plano-varios-estados',
        'status' => 1
    ],
];
