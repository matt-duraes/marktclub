<?php

namespace App\Classes\Saude\Operadoras;

use App\Classes\Saude\Plano;
use App\Classes\Saude\Regiao;
use Modules\Data;

class CentralNacionalUnimed extends AbstractOperadora
{
    protected array $acomodacoes = [
        Plano::CLASSICO_REGIONAL  => [
            'enfermaria' => 1
        ],
        Plano::ABSOLUTO           => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ],
        Plano::ESTILO_NACIONAL    => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ],
        Plano::EXCLUSIVO_NACIONAL => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ],
        Plano::SUPERIOR_NACIONAL  => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ]
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!array_key_exists($this->plano->indice(), $this->acomodacoes)) {
            return null;
        } elseif (!array_key_exists($this->acomodacao, $this->acomodacoes[$this->plano->indice()])) {
            return null;
        }
        return $this->acomodacoes[$this->plano->indice()][$this->acomodacao];
    }

    /**
     * @param Data|null $dataNascimento Data de Nascimento (opcional)
     *
     * @return float|null Valor da simulação, NULL caso error ao simular
     */
    public function simularValor(Data $dataNascimento = null): ?float
    {
        if ($dataNascimento !== null) {
            $this->idade = $this->pegarIdade($dataNascimento) ?? 0;
        }

        if ($this->idade <= 18) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 225.09],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 277.89, 'apartamento' => 341.82],
                    Plano::ABSOLUTO           => ['apartamento' => 376.56],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 484.86],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1240.57]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 224.37],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 277.00, 'apartamento' => 340.71],
                    Plano::ABSOLUTO           => ['apartamento' => 375.33],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 483.30],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1224.08]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 229.45],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 266.66, 'apartamento' => 314.23],
                    Plano::ABSOLUTO           => ['apartamento' => 383.83],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 494.23],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1254.93]
                ]
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 288.13],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 355.71, 'apartamento' => 437.53],
                    Plano::ABSOLUTO           => ['apartamento' => 481.99],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 620.63],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1587.97]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 287.20],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 354.57, 'apartamento' => 436.12],
                    Plano::ABSOLUTO           => ['apartamento' => 480.43],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 618.63],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1566.86]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 293.71],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 341.33, 'apartamento' => 402.22],
                    Plano::ABSOLUTO           => ['apartamento' => 491.31],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 632.63],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1606.35]
                ]
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 306.11],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 377.92, 'apartamento' => 464.84],
                    Plano::ABSOLUTO           => ['apartamento' => 512.08],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 659.37],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1687.07]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 305.12],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 376.70, 'apartamento' => 463.34],
                    Plano::ABSOLUTO           => ['apartamento' => 510.43],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 657.25],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1664.66]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 312.03],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 362.62, 'apartamento' => 427.33],
                    Plano::ABSOLUTO           => ['apartamento' => 521.98],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 672.11],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1706.61]
                ]
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 315.09],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 389.00, 'apartamento' => 478.47],
                    Plano::ABSOLUTO           => ['apartamento' => 527.10],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 678.70],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1736.55]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 314.08],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 387.75, 'apartamento' => 476.93],
                    Plano::ABSOLUTO           => ['apartamento' => 525.39],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 676.52],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1713.48]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 321.18],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 373.27, 'apartamento' => 439.86],
                    Plano::ABSOLUTO           => ['apartamento' => 537.29],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 691.83],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1756.66]
                ]
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 344.36],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 425.14, 'apartamento' => 522.92],
                    Plano::ABSOLUTO           => ['apartamento' => 576.06],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 741.75],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1897.88]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 343.25],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 423.77, 'apartamento' => 521.23],
                    Plano::ABSOLUTO           => ['apartamento' => 574.20],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 739.36],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1872.66]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 351.03],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 407.94, 'apartamento' => 480.72],
                    Plano::ABSOLUTO           => ['apartamento' => 587.20],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 756.10],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 1919.86]
                ]
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 393.87],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 486.26, 'apartamento' => 598.09],
                    Plano::ABSOLUTO           => ['apartamento' => 658.87],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 848.4],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 2170.72]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 392.59],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 484.69, 'apartamento' => 596.17],
                    Plano::ABSOLUTO           => ['apartamento' => 656.75],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 845.66],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 2141.86]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 401.49],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 466.59, 'apartamento' => 549.84],
                    Plano::ABSOLUTO           => ['apartamento' => 671.62],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 864.79],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 2195.86]
                ]
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 551.39],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 680.73, 'apartamento' => 837.3],
                    Plano::ABSOLUTO           => ['apartamento' => 922.39],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1187.7],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 3038.89]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 549.61],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 678.54, 'apartamento' => 834.6],
                    Plano::ABSOLUTO           => ['apartamento' => 919.41],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1183.87],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 2998.49]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 562.06],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 653.19, 'apartamento' => 769.73],
                    Plano::ABSOLUTO           => ['apartamento' => 940.23],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1210.67],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 3074.07]
                ]
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 738.22],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 911.39, 'apartamento' => 1121.01],
                    Plano::ABSOLUTO           => ['apartamento' => 1234.92],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1590.13],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 4068.55]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 735.84],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 908.44, 'apartamento' => 1117.38],
                    Plano::ABSOLUTO           => ['apartamento' => 1230.93],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1584.99],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 4014.47]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 752.50],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 874.51, 'apartamento' => 1030.54],
                    Plano::ABSOLUTO           => ['apartamento' => 1258.80],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1620.88],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 4115.66]
                ]
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 828.29],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 1022.58, 'apartamento' => 1257.77],
                    Plano::ABSOLUTO           => ['apartamento' => 1385.59],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1784.13],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 4564.92]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 825.62],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 1019.27, 'apartamento' => 1253.71],
                    Plano::ABSOLUTO           => ['apartamento' => 1381.12],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1778.36],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 4504.25]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 844.31],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 981.20, 'apartamento' => 1156.28],
                    Plano::ABSOLUTO           => ['apartamento' => 1412.38],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 1818.62],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 4617.77]
                ]
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Regiao::BRASILIA  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 1350.43],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 1667.19, 'apartamento' => 2050.64],
                    Plano::ABSOLUTO           => ['apartamento' => 2259.03],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 2908.81],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 7442.54]
                ],
                Regiao::SALVADOR  => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 1346.06],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 1661.81, 'apartamento' => 2044.01],
                    Plano::ABSOLUTO           => ['apartamento' => 2251.73],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 2899.41],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 7343.61]
                ],
                Regiao::SAO_PAULO => [
                    Plano::CLASSICO_REGIONAL  => ['enfermaria' => 1376.53],
                    Plano::ESTILO_NACIONAL    => ['enfermaria' => 1599.74, 'apartamento' => 1885.17],
                    Plano::ABSOLUTO           => ['apartamento' => 2302.71],
                    Plano::SUPERIOR_NACIONAL  => ['apartamento' => 2965.05],
                    Plano::EXCLUSIVO_NACIONAL => ['apartamento' => 7528.71]
                ]
            ];
        }

        if (!array_key_exists($this->regiao->indice(), $this->valores)) {
            return null;
        } elseif (!array_key_exists($this->plano->indice(), $this->valores[$this->regiao->indice()])) {
            return null;
        } elseif (
            !array_key_exists(
                $this->acomodacao,
                $this->valores[$this->regiao->indice()][$this->plano->indice()]
            )
        ) {
            return null;
        }
        return $this->valores[$this->regiao->indice()][$this->plano->indice()][$this->acomodacao];
    }
}
