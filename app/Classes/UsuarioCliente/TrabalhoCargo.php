<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TrabalhoCargo extends Status
{
    private array $listaPorEmpresa = [
        0 => [
            'lista' => [
                'cargo-teste-01' => 'Cargo de Teste 01'
            ],
            'numero' => [2000]
        ],
        1 => [
            'lista' => [
                'desenvolvedor' => 'Desenvolvedor'
            ],
            'numero' => [1000]
        ],
        19 => [
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
        $idEmpresa = 0;
        if (defined('TOKEN')) {
            $idEmpresa = TOKEN['empresa']->get('id');
        }
        parent::__construct(
            lista: array_key_exists($idEmpresa, $this->listaPorEmpresa) ?
                $this->listaPorEmpresa[$idEmpresa]['lista'] : [],
            numero: array_key_exists($idEmpresa, $this->listaPorEmpresa) ?
                $this->listaPorEmpresa[$idEmpresa]['numero'] : [],
        );
    }
}
