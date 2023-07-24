<?php

namespace App\Classes\Saude\Operadoras;

use App\Classes\Saude\Plano;
use App\Classes\Saude\Regiao;
use Exception;
use Modules\Data;

class Amil extends AbstractOperadora
{
    protected array $acomodacoes = [
        Plano::AMIL_S60QC_RJ      => [
            'coletivo' => 8
        ],
        Plano::AMIL_S80QC         => [
            'coletivo' => 8
        ],
        Plano::AMIL_S380QC        => [
            'coletivo' => 8
        ],
        Plano::AMIL_S450QC        => [
            'coletivo' => 8
        ],
        Plano::AMIL_S60QC_JUNDIAI => [
            'coletivo' => 8
        ],
        Plano::AMIL_S60QC_SP      => [
            'coletivo' => 8
        ],
        Plano::AMIL_S75QC         => [
            'coletivo' => 8
        ],
        Plano::AMIL_S80QP         => [
            'individual' => 9
        ],
        Plano::AMIL_S380QP        => [
            'individual' => 9
        ],
        Plano::AMIL_S450QP        => [
            'individual' => 9
        ],
        Plano::AMIL_S750R1        => [
            'individual' => 9
        ],
        Plano::AMIL_S750R2        => [
            'individual' => 9
        ],
        Plano::AMIL_S580QP        => [
            'individual' => 9
        ],
        Plano::AMIL_S75QP         => [
            'individual' => 9
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
     * @throws Exception
     */
    public function simularValor(Data $dataNascimento = null): ?float
    {
        if ($dataNascimento !== null) {
            $this->idade = $this->pegarIdade($dataNascimento) ?? 0;
        }

        if ($this->idade <= 18) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 342.78],
                    Plano::AMIL_S80QP  => ['individual' => 370.20],
                    Plano::AMIL_S380QC => ['coletivo' => 512.96],
                    Plano::AMIL_S380QP => ['individual' => 548.84],
                    Plano::AMIL_S450QC => ['coletivo' => 564.79],
                    Plano::AMIL_S450QP => ['individual' => 604.34],
                    Plano::AMIL_S750R1 => ['individual' => 628.83],
                    Plano::AMIL_S750R2 => ['individual' => 635.07]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 158.93],
                    Plano::AMIL_S380QC   => ['coletivo' => 293.05],
                    Plano::AMIL_S75QC    => ['coletivo' => 225.35],
                    Plano::AMIL_S75QP    => ['individual' => 243.38],
                    Plano::AMIL_S380QP   => ['individual' => 325.28],
                    Plano::AMIL_S450QC   => ['coletivo' => 333.71],
                    Plano::AMIL_S450QP   => ['individual' => 370.44],
                    Plano::AMIL_S580QP   => ['individual' => 412.19],
                    Plano::AMIL_S750R1   => ['individual' => 417.37],
                    Plano::AMIL_S750R2   => ['individual' => 421.52]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 177.42],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 144.63],
                    Plano::AMIL_S80QC         => ['coletivo' => 309.50],
                    Plano::AMIL_S80QP         => ['individual' => 334.27],
                    Plano::AMIL_S380QC        => ['coletivo' => 384.88],
                    Plano::AMIL_S380QP        => ['individual' => 427.24],
                    Plano::AMIL_S450QC        => ['coletivo' => 420.72],
                    Plano::AMIL_S450QP        => ['individual' => 467.02],
                    Plano::AMIL_S580QP        => ['individual' => 520.51],
                    Plano::AMIL_S750R1        => ['individual' => 620.97],
                    Plano::AMIL_S750R2        => ['individual' => 627.14]
                ]
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 401.07],
                    Plano::AMIL_S80QP  => ['individual' => 433.14],
                    Plano::AMIL_S380QC => ['coletivo' => 600.16],
                    Plano::AMIL_S380QP => ['individual' => 642.15],
                    Plano::AMIL_S450QC => ['coletivo' => 660.79],
                    Plano::AMIL_S450QP => ['individual' => 707.07],
                    Plano::AMIL_S750R1 => ['individual' => 735.73],
                    Plano::AMIL_S750R2 => ['individual' => 906.50]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 215.81],
                    Plano::AMIL_S380QC   => ['coletivo' => 342.88],
                    Plano::AMIL_S75QC    => ['coletivo' => 263.66],
                    Plano::AMIL_S75QP    => ['individual' => 284.76],
                    Plano::AMIL_S380QP   => ['individual' => 380.57],
                    Plano::AMIL_S450QC   => ['coletivo' => 390.44],
                    Plano::AMIL_S450QP   => ['individual' => 433.41],
                    Plano::AMIL_S580QP   => ['individual' => 482.26],
                    Plano::AMIL_S750R1   => ['individual' => 488.33],
                    Plano::AMIL_S750R2   => ['individual' => 493.18]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 240.92],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 196.40],
                    Plano::AMIL_S80QC         => ['coletivo' => 362.12],
                    Plano::AMIL_S80QP         => ['individual' => 391.09],
                    Plano::AMIL_S380QC        => ['coletivo' => 450.30],
                    Plano::AMIL_S380QP        => ['individual' => 499.87],
                    Plano::AMIL_S450QC        => ['coletivo' => 492.25],
                    Plano::AMIL_S450QP        => ['individual' => 546.40],
                    Plano::AMIL_S580QP        => ['individual' => 609.00],
                    Plano::AMIL_S750R1        => ['individual' => 726.54],
                    Plano::AMIL_S750R2        => ['individual' => 733.76]
                ]
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 489.29],
                    Plano::AMIL_S80QP  => ['individual' => 528.44],
                    Plano::AMIL_S380QC => ['coletivo' => 732.18],
                    Plano::AMIL_S380QP => ['individual' => 783.43],
                    Plano::AMIL_S450QC => ['coletivo' => 806.18],
                    Plano::AMIL_S450QP => ['individual' => 862.63],
                    Plano::AMIL_S750R1 => ['individual' => 897.60],
                    Plano::AMIL_S750R2 => ['individual' => 906.50]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 253.34],
                    Plano::AMIL_S380QC   => ['coletivo' => 418.30],
                    Plano::AMIL_S75QC    => ['coletivo' => 321.67],
                    Plano::AMIL_S75QP    => ['individual' => 347.40],
                    Plano::AMIL_S380QP   => ['individual' => 464.31],
                    Plano::AMIL_S450QC   => ['coletivo' => 476.36],
                    Plano::AMIL_S450QP   => ['individual' => 528.76],
                    Plano::AMIL_S580QP   => ['individual' => 588.36],
                    Plano::AMIL_S750R1   => ['individual' => 595.77],
                    Plano::AMIL_S750R2   => ['individual' => 601.68]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 282.83],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 230, .4],
                    Plano::AMIL_S80QC         => ['coletivo' => 441.78],
                    Plano::AMIL_S80QP         => ['individual' => 477.11],
                    Plano::AMIL_S380QC        => ['coletivo' => 549.37],
                    Plano::AMIL_S380QP        => ['individual' => 609.83],
                    Plano::AMIL_S450QC        => ['coletivo' => 600.53],
                    Plano::AMIL_S450QP        => ['individual' => 666.61],
                    Plano::AMIL_S580QP        => ['individual' => 742, 97],
                    Plano::AMIL_S750R1        => ['individual' => 886.38],
                    Plano::AMIL_S750R2        => ['individual' => 895.20]
                ]
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 587.15],
                    Plano::AMIL_S80QP  => ['individual' => 634.12],
                    Plano::AMIL_S380QC => ['coletivo' => 878.63],
                    Plano::AMIL_S380QP => ['individual' => 940.10],
                    Plano::AMIL_S450QC => ['coletivo' => 967.41],
                    Plano::AMIL_S450QP => ['individual' => 1035.16],
                    Plano::AMIL_S750R1 => ['individual' => 1077.11],
                    Plano::AMIL_S750R2 => ['individual' => 1087.82]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 253.34],
                    Plano::AMIL_S380QC   => ['coletivo' => 501.96],
                    Plano::AMIL_S75QC    => ['coletivo' => 386.00],
                    Plano::AMIL_S75QP    => ['individual' => 416.88],
                    Plano::AMIL_S380QP   => ['individual' => 557.17],
                    Plano::AMIL_S450QC   => ['coletivo' => 571.62],
                    Plano::AMIL_S450QP   => ['individual' => 634.53],
                    Plano::AMIL_S580QP   => ['individual' => 706.04],
                    Plano::AMIL_S750R1   => ['individual' => 714.91],
                    Plano::AMIL_S750R2   => ['individual' => 722.02]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 282.83],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 230.54],
                    Plano::AMIL_S80QC         => ['coletivo' => 530.13],
                    Plano::AMIL_S80QP         => ['individual' => 572.54],
                    Plano::AMIL_S380QC        => ['coletivo' => 659.24],
                    Plano::AMIL_S380QP        => ['individual' => 731.79],
                    Plano::AMIL_S450QC        => ['coletivo' => 720.65],
                    Plano::AMIL_S450QP        => ['individual' => 799.94],
                    Plano::AMIL_S580QP        => ['individual' => 891.57],
                    Plano::AMIL_S750R1        => ['individual' => 1063.66],
                    Plano::AMIL_S750R2        => ['individual' => 1074.22]
                ]
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 616.51],
                    Plano::AMIL_S80QP  => ['individual' => 665.83],
                    Plano::AMIL_S380QC => ['coletivo' => 922.56],
                    Plano::AMIL_S380QP => ['individual' => 987.10],
                    Plano::AMIL_S450QC => ['coletivo' => 1015.79],
                    Plano::AMIL_S450QP => ['individual' => 1086.92],
                    Plano::AMIL_S750R1 => ['individual' => 1130.96],
                    Plano::AMIL_S750R2 => ['individual' => 1142.20]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 253.34],
                    Plano::AMIL_S380QC   => ['coletivo' => 527.06],
                    Plano::AMIL_S75QC    => ['coletivo' => 405.30],
                    Plano::AMIL_S75QP    => ['individual' => 437.73],
                    Plano::AMIL_S380QP   => ['individual' => 585.03],
                    Plano::AMIL_S450QC   => ['coletivo' => 600.20],
                    Plano::AMIL_S450QP   => ['individual' => 666.24],
                    Plano::AMIL_S580QP   => ['individual' => 741.34],
                    Plano::AMIL_S750R1   => ['individual' => 750.66],
                    Plano::AMIL_S750R2   => ['individual' => 758.12]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 282.83],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 230.54],
                    Plano::AMIL_S80QC         => ['coletivo' => 556.64],
                    Plano::AMIL_S80QP         => ['individual' => 601.18],
                    Plano::AMIL_S380QC        => ['coletivo' => 692.22],
                    Plano::AMIL_S380QP        => ['individual' => 768.37],
                    Plano::AMIL_S450QC        => ['coletivo' => 756.69],
                    Plano::AMIL_S450QP        => ['individual' => 839.93],
                    Plano::AMIL_S580QP        => ['individual' => 936.15],
                    Plano::AMIL_S750R1        => ['individual' => 1116.85],
                    Plano::AMIL_S750R2        => ['individual' => 1127.93]
                ]
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 678.17],
                    Plano::AMIL_S80QP  => ['individual' => 732.41],
                    Plano::AMIL_S380QC => ['coletivo' => 1014.81],
                    Plano::AMIL_S380QP => ['individual' => 1085.83],
                    Plano::AMIL_S450QC => ['coletivo' => 1117.36],
                    Plano::AMIL_S450QP => ['individual' => 1195.61],
                    Plano::AMIL_S750R1 => ['individual' => 1244.07],
                    Plano::AMIL_S750R2 => ['individual' => 1256.42]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 282.98],
                    Plano::AMIL_S380QC   => ['coletivo' => 579.75],
                    Plano::AMIL_S75QC    => ['coletivo' => 445.84],
                    Plano::AMIL_S75QP    => ['individual' => 481.50],
                    Plano::AMIL_S380QP   => ['individual' => 643.53],
                    Plano::AMIL_S450QC   => ['coletivo' => 660.23],
                    Plano::AMIL_S450QP   => ['individual' => 732.86],
                    Plano::AMIL_S580QP   => ['individual' => 815.47],
                    Plano::AMIL_S750R1   => ['individual' => 825.73],
                    Plano::AMIL_S750R2   => ['individual' => 833.93]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 315.93],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 257.52],
                    Plano::AMIL_S80QC         => ['coletivo' => 612.30],
                    Plano::AMIL_S80QP         => ['individual' => 661.28],
                    Plano::AMIL_S380QC        => ['coletivo' => 761.42],
                    Plano::AMIL_S380QP        => ['individual' => 845.21],
                    Plano::AMIL_S450QC        => ['coletivo' => 832.35],
                    Plano::AMIL_S450QP        => ['individual' => 923.93],
                    Plano::AMIL_S580QP        => ['individual' => 1029.76],
                    Plano::AMIL_S750R1        => ['individual' => 1228.52],
                    Plano::AMIL_S750R2        => ['individual' => 1240.73]
                ]
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 847.69],
                    Plano::AMIL_S80QP  => ['individual' => 915.52],
                    Plano::AMIL_S380QC => ['coletivo' => 1268.52],
                    Plano::AMIL_S380QP => ['individual' => 1357.27],
                    Plano::AMIL_S450QC => ['coletivo' => 1396.70],
                    Plano::AMIL_S450QP => ['individual' => 1494.52],
                    Plano::AMIL_S750R1 => ['individual' => 1555.08],
                    Plano::AMIL_S750R2 => ['individual' => 1570.52]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 390.79],
                    Plano::AMIL_S380QC   => ['coletivo' => 724.71],
                    Plano::AMIL_S75QC    => ['coletivo' => 557.29],
                    Plano::AMIL_S75QP    => ['individual' => 601.88],
                    Plano::AMIL_S380QP   => ['individual' => 804.42],
                    Plano::AMIL_S450QC   => ['coletivo' => 825.28],
                    Plano::AMIL_S450QP   => ['individual' => 916.08],
                    Plano::AMIL_S580QP   => ['individual' => 1019.34],
                    Plano::AMIL_S750R1   => ['individual' => 1032.17],
                    Plano::AMIL_S750R2   => ['individual' => 1042.42]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 436.28],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 355.65],
                    Plano::AMIL_S80QC         => ['coletivo' => 765.38],
                    Plano::AMIL_S80QP         => ['individual' => 826.62],
                    Plano::AMIL_S380QC        => ['coletivo' => 951.77],
                    Plano::AMIL_S380QP        => ['individual' => 1056.52],
                    Plano::AMIL_S450QC        => ['coletivo' => 1040.44],
                    Plano::AMIL_S450QP        => ['individual' => 1154.91],
                    Plano::AMIL_S580QP        => ['individual' => 1287.20],
                    Plano::AMIL_S750R1        => ['individual' => 1535.66],
                    Plano::AMIL_S750R2        => ['individual' => 1550.93]
                ]
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 932.47],
                    Plano::AMIL_S80QP  => ['individual' => 1007.06],
                    Plano::AMIL_S380QC => ['coletivo' => 1395.37],
                    Plano::AMIL_S380QP => ['individual' => 1492.99],
                    Plano::AMIL_S450QC => ['coletivo' => 1536.37],
                    Plano::AMIL_S450QP => ['individual' => 1643.95],
                    Plano::AMIL_S750R1 => ['individual' => 1710.58],
                    Plano::AMIL_S750R2 => ['individual' => 1727.58]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 466.60],
                    Plano::AMIL_S380QC   => ['coletivo' => 797.18],
                    Plano::AMIL_S75QC    => ['coletivo' => 613.02],
                    Plano::AMIL_S75QP    => ['individual' => 662.07],
                    Plano::AMIL_S380QP   => ['individual' => 884.86],
                    Plano::AMIL_S450QC   => ['coletivo' => 907.81],
                    Plano::AMIL_S450QP   => ['individual' => 1007.70],
                    Plano::AMIL_S580QP   => ['individual' => 1121.27],
                    Plano::AMIL_S750R1   => ['individual' => 1135.37],
                    Plano::AMIL_S750R2   => ['individual' => 1146.65]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 520.92],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 424.63],
                    Plano::AMIL_S80QC         => ['coletivo' => 841.92],
                    Plano::AMIL_S80QP         => ['individual' => 909.27],
                    Plano::AMIL_S380QC        => ['coletivo' => 1046.96],
                    Plano::AMIL_S380QP        => ['individual' => 1162.17],
                    Plano::AMIL_S450QC        => ['coletivo' => 1144.48],
                    Plano::AMIL_S450QP        => ['individual' => 1270.41],
                    Plano::AMIL_S580QP        => ['individual' => 1415.92],
                    Plano::AMIL_S750R1        => ['individual' => 1689.22],
                    Plano::AMIL_S750R2        => ['individual' => 1706.00]
                ]
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 1165.58],
                    Plano::AMIL_S80QP  => ['individual' => 1258.83],
                    Plano::AMIL_S380QC => ['coletivo' => 1744.21],
                    Plano::AMIL_S380QP => ['individual' => 1866.24],
                    Plano::AMIL_S450QC => ['coletivo' => 1920.47],
                    Plano::AMIL_S450QP => ['individual' => 2054.94],
                    Plano::AMIL_S750R1 => ['individual' => 2138.24],
                    Plano::AMIL_S750R2 => ['individual' => 2159.47]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 670.98],
                    Plano::AMIL_S380QC   => ['coletivo' => 996.47],
                    Plano::AMIL_S75QC    => ['coletivo' => 766.28],
                    Plano::AMIL_S75QP    => ['individual' => 827.58],
                    Plano::AMIL_S380QP   => ['individual' => 1106.07],
                    Plano::AMIL_S450QC   => ['coletivo' => 1134.76],
                    Plano::AMIL_S450QP   => ['individual' => 1259.61],
                    Plano::AMIL_S580QP   => ['individual' => 1401.59],
                    Plano::AMIL_S750R1   => ['individual' => 1419.21],
                    Plano::AMIL_S750R2   => ['individual' => 1433.32]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 749.08],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 610.63],
                    Plano::AMIL_S80QC         => ['coletivo' => 1052.39],
                    Plano::AMIL_S80QP         => ['individual' => 1136.58],
                    Plano::AMIL_S380QC        => ['coletivo' => 1308.69],
                    Plano::AMIL_S380QP        => ['individual' => 1452.71],
                    Plano::AMIL_S450QC        => ['coletivo' => 1430.61],
                    Plano::AMIL_S450QP        => ['individual' => 1588.01],
                    Plano::AMIL_S580QP        => ['individual' => 1769.90],
                    Plano::AMIL_S750R1        => ['individual' => 2111.53],
                    Plano::AMIL_S750R2        => ['individual' => 2132.51]
                ]
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Regiao::BRASILIA       => [
                    Plano::AMIL_S80QC  => ['coletivo' => 2039.77],
                    Plano::AMIL_S80QP  => ['individual' => 2202.95],
                    Plano::AMIL_S380QC => ['coletivo' => 3052.38],
                    Plano::AMIL_S380QP => ['individual' => 3265.93],
                    Plano::AMIL_S450QC => ['coletivo' => 3360.81],
                    Plano::AMIL_S450QP => ['individual' => 3596.16],
                    Plano::AMIL_S750R1 => ['individual' => 3741.91],
                    Plano::AMIL_S750R2 => ['individual' => 3779.08]
                ],
                Regiao::RIO_DE_JANEIRO => [
                    Plano::AMIL_S60QC_RJ => ['coletivo' => 951.47],
                    Plano::AMIL_S380QC   => ['coletivo' => 1743.81],
                    Plano::AMIL_S75QC    => ['coletivo' => 1340.99],
                    Plano::AMIL_S75QP    => ['individual' => 1448.27],
                    Plano::AMIL_S380QP   => ['individual' => 1935.62],
                    Plano::AMIL_S450QC   => ['coletivo' => 1985.83],
                    Plano::AMIL_S450QP   => ['individual' => 2204.31],
                    Plano::AMIL_S580QP   => ['individual' => 2452.78],
                    Plano::AMIL_S750R1   => ['individual' => 2483.64],
                    Plano::AMIL_S750R2   => ['individual' => 2508.31]
                ],
                Regiao::SAO_PAULO      => [
                    Plano::AMIL_S60QC_JUNDIAI => ['coletivo' => 1062.19],
                    Plano::AMIL_S60QC_SP      => ['coletivo' => 865.87],
                    Plano::AMIL_S80QC         => ['coletivo' => 1841.70],
                    Plano::AMIL_S80QP         => ['individual' => 1989.03],
                    Plano::AMIL_S380QC        => ['coletivo' => 2290.24],
                    Plano::AMIL_S380QP        => ['individual' => 2542.24],
                    Plano::AMIL_S450QC        => ['coletivo' => 2503.55],
                    Plano::AMIL_S450QP        => ['individual' => 2779.01],
                    Plano::AMIL_S580QP        => ['individual' => 3097.33],
                    Plano::AMIL_S750R1        => ['individual' => 3695.19],
                    Plano::AMIL_S750R2        => ['individual' => 3731.88]
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
