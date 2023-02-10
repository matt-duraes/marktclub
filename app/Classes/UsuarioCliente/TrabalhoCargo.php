<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TrabalhoCargo extends Status
{
    const EMPRESA = [
        'geral' => [
            'lista' => [
                'cargo' => 'Cargo'
            ],
            'numero' => [2000]
        ],
        'marktclub' => [
            'lista' => [
                'desenvolvedor' => 'Desenvolvedor'
            ],
            'numero' => [1000]
        ],
        'unareg' => [
            'lista' => [
                'analista-administrativo' => 'Analista Administrativo',
                'especialista' => 'Especialista',
                'especialista-geoprocessamento' => 'Especialista em Geoprocessamento',
                'especialista-regulacao' => 'Especialista em Regulação',
                'tecnico-administrativo' => 'Técnico Administrativo',
                'tecnico-regulacao' => 'Técnico em Regulação',
                'especialista-recuros-minerais' => 'Especialista em Recursos Minerais',
                'tecnico-atividades-mineracao' => 'Técnico em Atividades de Mineração',
            ],
            'numero' => [1, 2, 3, 4, 5, 6, 7, 8]
        ]
    ];

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
