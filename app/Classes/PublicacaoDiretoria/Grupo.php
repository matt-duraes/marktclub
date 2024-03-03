<?php

namespace App\Classes\PublicacaoDiretoria;

use Status\Status;

final class Grupo extends Status
{
    public const EMPRESA = [
        'geral'  => [
            'lista'  => [
                'diretoria' => 'Diretoria Executiva'
            ],
            'numero' => [1]
        ],
        'marktclub'  => [
            'lista'  => [
                'diretoria' => 'Diretoria Executiva',
                'conselho'  => 'Conselho Fiscal'
            ],
            'numero' => [1, 2]
        ],
        'sinpefrs' => [
            'lista'  => [
                'diretoria'       => 'Diretoria Executiva',
                'conselho-fiscal' => 'Conselho Fiscal'
            ],
            'numero' => [1, 2]
        ],
        'sinpefpr' => [
            'lista'  => [
                'diretoria'              => 'Diretoria Executiva',
                'conselho-fiscal'        => 'Conselho Fiscal',
                'representante-estadual' => 'Representantes Estaduais',
            ],
            'numero' => [1, 2, 3]
        ]
    ];

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
