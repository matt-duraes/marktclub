<?php

namespace App\Classes\Saude;

class Helper
{
    public const ACOMODACAO = [
        Operadora::UNIMED                          => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ],
        Operadora::UNIMED_SEGURO                   => [
            'basico'   => 3,
            'pratico'  => 4,
            'versatil' => 5
        ],
        Operadora::CENTRAL_NACIONAL_UNIMED         => [
            Tipo::CLASSICO_REGIONAL  => [
                'enfermaria' => 1
            ],
            Tipo::ABSOLUTO           => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::ESTILO_NACIONAL    => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::EXCLUSIVO_NACIONAL => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::SUPERIOR_NACIONAL  => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ]
        ],
        Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => [
            Tipo::REGIONAL           => [
                'enfermaria-50' => 6,
                'enfermaria-30' => 7
            ],
            Tipo::ABSOLUTO           => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::CLASSICO_REGIONAL  => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::ESTILO_NACIONAL    => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::EXCLUSIVO_NACIONAL => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ],
            Tipo::SUPERIOR_NACIONAL  => [
                'enfermaria'  => 1,
                'apartamento' => 2
            ]
        ],
        Operadora::AMIL                            => [
            Tipo::AMIL_S60QC_RJ      => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S80QC         => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S380QC        => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S450QC        => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S60QC_JUNDIAI => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S60QC_SP      => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S75QC         => [
                'coletivo' => 8
            ],
            Tipo::AMIL_S80QP         => [
                'individual' => 9
            ],
            Tipo::AMIL_S380QP        => [
                'individual' => 9
            ],
            Tipo::AMIL_S450QP        => [
                'individual' => 9
            ],
            Tipo::AMIL_S750R1        => [
                'individual' => 9
            ],
            Tipo::AMIL_S750R2        => [
                'individual' => 9
            ],
            Tipo::AMIL_S580QP        => [
                'individual' => 9
            ],
            Tipo::AMIL_S75QP         => [
                'individual' => 9
            ]
        ]
    ];
}
