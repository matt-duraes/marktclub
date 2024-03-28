<?php

namespace App\Classes\PublicacaoLista;

use Status\Status;

final class Grupo extends Status
{
    public const EMPRESA = [
        'geral'  => [
            'lista'  => [
                'geral' => 'Geral'
            ],
            'numero' => [1]
        ],
        'sinpefrs' => [
            'lista'  => [
                'representacoes-sindicais' => 'Representações sindicais'
            ],
            'numero' => [2]
        ],
        'sinpefpr' => [
            'lista'  => [
                'delegacia-sindical' => 'Delegacias sindicais'
            ],
            'numero' => [3]
        ],
        'sinjutra' => [
            'lista'  => [
                'sindicatos' => 'Sindicatos',
                'comites' => 'Comitês',
                'carreiras' => 'Carreiras',
                'processos' => 'Processos',
                'midias' => 'Mídia',
                'campanhas' => 'Campanhas'
            ],
            'numero' => [4, 5, 6, 7, 8, 9]
        ]
    ];

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
