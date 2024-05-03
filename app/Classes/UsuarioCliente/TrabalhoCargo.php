<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TrabalhoCargo extends Status
{
    public const EMPRESA = [
        'geral'     => [
            'lista'  => [
                'geral' => 'Geral'
            ],
            'numero' => [2000]
        ],
        'marktclub' => [
            'lista'  => [
                'desenvolvedor' => 'Desenvolvedor'
            ],
            'numero' => [1000]
        ],
        'unareg'    => [
            'lista'  => [
                'analista-administrativo'       => 'Analista Administrativo',
                'especialista'                  => 'Especialista',
                'especialista-geoprocessamento' => 'Especialista em Geoprocessamento',
                'especialista-regulacao'        => 'Especialista em Regulação',
                'tecnico-administrativo'        => 'Técnico Administrativo',
                'tecnico-regulacao'             => 'Técnico em Regulação',
                'especialista-recuros-minerais' => 'Especialista em Recursos Minerais',
                'tecnico-atividades-mineracao'  => 'Técnico em Atividades de Mineração',
                'colaborador'                   => 'Colaborador UNAREG'
            ],
            'numero' => [1, 2, 3, 4, 5, 6, 7, 8, 9]
        ]
    ];

    public function __construct(
        protected string|int|null $valor = null,
        bool $geral = false
    ) {
        parent::__construct(empresa: self::EMPRESA, geral: $geral);
    }
}
