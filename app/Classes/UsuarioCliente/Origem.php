<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Origem extends Status
{
    private array $listaPorEmpresa = [
        0 => [
            'lista' => [
                'facebook' => 'Facebook',
                'google' => 'Google',
                'instagram' => 'Instagram',
                'outro' => 'Outros'
            ],
            'numero' => [10, 11, 12, 7]
        ],
        197 => [
            'lista' => [
                'banco-investimento' => 'Cliente banco de investimento',
                'cliente-seguradora' => 'Cliente seguradora',
                'consignado-convenio-publico' => 'Consignado convênio público',
                'consignado-empresa-privada' => 'Consignado de empresa privada',
                'funcionario-concessionaria' => 'Funcionário de concessionária',
                'colaborador-alfa' => 'Colaborador Alfa',
                'outro' => 'Outros'
            ],
            'numero' => [1, 2, 3, 4, 5, 6, 7]
        ]
    ];

    public function __construct(
        protected null|string|int $valor = null
    ) {
        $idEmpresa = defined('TOKEN') &&
            is_array(TOKEN) &&
            array_key_exists('empresa', TOKEN) &&
            array_key_exists(TOKEN['empresa']->get('id'), $this->listaPorEmpresa) ? TOKEN['empresa']->get('id') : 0;

        parent::__construct(
            lista: array_key_exists($idEmpresa, $this->listaPorEmpresa) ?
                $this->listaPorEmpresa[$idEmpresa]['lista'] : [],
            numero: array_key_exists($idEmpresa, $this->listaPorEmpresa) ?
                $this->listaPorEmpresa[$idEmpresa]['numero'] : [],
        );
    }
}
