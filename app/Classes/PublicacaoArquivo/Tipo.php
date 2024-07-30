<?php

namespace App\Classes\PublicacaoArquivo;

use Status\Status as StatusStatus;

class Tipo extends StatusStatus
{
    public const EMPRESA = [
        'geral'   => [
            'lista'  => [
                'geral' => 'Geral'
            ],
            'numero' => [1]
        ],
        'intelis' => [
            'lista'  => [
                'estatuto'             => 'Estatuto',
                'notas-juridicas'      => 'Notas Jurídicas',
                'pareceres'            => 'Pareceres',
                'relatorios-processos' => 'Relatórios Processos'
            ],
            'numero' => [2, 3, 4, 5]
        ]
    ];

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
