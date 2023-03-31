<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Origem extends Status
{
    public const EMPRESA = [
        'geral' => [
            'lista' => [
                'facebook' => 'Facebook',
                'google' => 'Google',
                'instagram' => 'Instagram',
                'outro' => 'Outros'
            ],
            'numero' => [10, 11, 12, 7]
        ],
        'alfa' => [
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
        parent::__construct(empresa: self::EMPRESA);
    }
}
