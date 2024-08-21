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
                'desenvolvedor' => 'Desenvolvedor',
            ],
            'numero' => [1000]
        ],
        'sinjutra'  => [
            'lista'  => [
                'aux-jud'                 => 'AUXILIAR JUDICIÁRIO',
                'tec-jud'                 => 'TÉCNICO JUDICIÁRIO',
                'tec-jud-agente-pj'       => 'TÉCNICO JUDICIÁRIO AGENTE DA POLÍCIA JUDICIAL',
                'tec-jud-artes-graficas'  => 'TÉCNICO JUDICIÁRIO ARTES GRÁFICAS',
                'tec-jud-carpintaria'     => 'TÉCNICO JUDICIÁRIO CARPINTARIA E MARCENARIA',
                'tec-jud-edificacoes'     => 'TÉCNICO JUDICIÁRIO EDIFICAÇÕES E METALURGIA',
                'tec-jud-enfermagem'      => 'TÉCNICO JUDICIÁRIO ENFERMAGEM',
                'tec-jud-logico-eletro'   => 'TÉCNICO JUDICIÁRIO INSTALAÇÕES LÓGICO-ELÉTRICAS',
                'tec-jud-portaria'        => 'TÉCNICO JUDICIÁRIO PORTARIA',
                'tec-jud-telecom'         => 'TÉCNICO JUDICIÁRIO TELECOM. E ELETRICIDADE',
                'tec-jud-telefonia'       => 'TÉCNICO JUDICIÁRIO TELEFONIA',
                'tec-jud-ti'              => 'TÉCNICO JUDICIÁRIO TI',
                'tec-jud-transporte'      => 'TÉCNICO JUDICIÁRIO TRANSPORTE',
                'analista-judiciario'     => 'ANALISTA JUDICIÁRIO',
                'ana-jud-arquitetura'     => 'ANALISTA JUDICIÁRIO ARQUITETURA',
                'ana-jud-biblioteconomia' => 'ANALISTA JUDICIÁRIO BIBLIOTECONOMIA',
                'ana-jud-contabilidade'   => 'ANALISTA JUDICIÁRIO CONTABILIDADE',
                'ana-jud-enfermagem'      => 'ANALISTA JUDICIÁRIO ENFERMAGEM',
                'ana-jud-eng-civil'       => 'ANALISTA JUDICIÁRIO ENGENHARIA (CIVIL)',
                'ana-jud-eng-eletrica'    => 'ANALISTA JUDICIÁRIO ENGENHARIA (ELÉTRICA)',
                'ana-jud-estatistica'     => 'ANALISTA JUDICIÁRIO ESTATÍSTICA',
                'ana-jud-fisioterapia'    => 'ANALISTA JUDICIÁRIO FISIOTERAPIA',
                'ana-jud-medicina'        => 'ANALISTA JUDICIÁRIO MEDICINA',
                'ana-jud-odontologia'     => 'ANALISTA JUDICIÁRIO ODONTOLOGIA',
                'ana-jud-ofc-justica'     => 'ANALISTA JUDICIÁRIO OFIC JUSTIÇA AVALIADOR FEDERAL',
                'ana-jud-psicologia'      => 'ANALISTA JUDICIÁRIO PSICOLOGIA',
                'ana-jud-servico-social'  => 'ANALISTA JUDICIÁRIO SERVIÇO SOCIAL',
                'ana-jud-ti'              => 'ANALISTA JUDICIÁRIO TI'
            ],
            'numero' => [
                3000, 3001, 3002, 3003, 3004, 3005, 3006, 3007, 3008, 3009, 3010,
                3011, 3012, 3013, 3014, 3015, 3016, 3017, 3018, 3019, 3020, 3021,
                3022, 3023, 3024, 3025, 3026
            ]
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
