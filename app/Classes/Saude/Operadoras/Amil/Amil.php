<?php

namespace App\Classes\Saude\Operadoras\Amil;

use App\Classes\Saude\Acomodacao;
use App\Classes\Saude\Operadoras\AbstractOperadora;
use Exception;
use Modules\Data;

class Amil extends AbstractOperadora
{
    protected array $planos = [
        Regioes::BRASILIA       => [
            Planos::AMIL_S80QC, Planos::AMIL_S80QP, Planos::AMIL_S380QC,
            Planos::AMIL_S380QP, Planos::AMIL_S450QC, Planos::AMIL_S450QP,
            Planos::AMIL_S750R1, Planos::AMIL_S750R2
        ],
        Regioes::RIO_DE_JANEIRO => [
            Planos::AMIL_S60QC_RJ, Planos::AMIL_S380QC, Planos::AMIL_S75QC,
            Planos::AMIL_S75QP, Planos::AMIL_S380QP, Planos::AMIL_S450QC,
            Planos::AMIL_S450QP, Planos::AMIL_S580QP, Planos::AMIL_S750R1,
            Planos::AMIL_S750R2
        ],
        Regioes::SAO_PAULO      => [
            Planos::AMIL_S60QC_JUNDIAI, Planos::AMIL_S60QC_SP, Planos::AMIL_S80QC,
            Planos::AMIL_S80QP, Planos::AMIL_S380QC, Planos::AMIL_S380QP,
            Planos::AMIL_S450QC, Planos::AMIL_S450QP, Planos::AMIL_S580QP,
            Planos::AMIL_S750R1, Planos::AMIL_S750R2
        ]
    ];
    protected array $acomodacoes = [
        Acomodacao::ACOMODACAO_INDIVIDUAL => [
            Planos::AMIL_S80QP, Planos::AMIL_S380QP, Planos::AMIL_S450QP,
            Planos::AMIL_S750R1, Planos::AMIL_S750R2, Planos::AMIL_S580QP,
            Planos::AMIL_S75QP
        ],
        Acomodacao::ACOMODACAO_COLETIVA   => [
            Planos::AMIL_S60QC_RJ, Planos::AMIL_S80QC, Planos::AMIL_S380QC,
            Planos::AMIL_S450QC, Planos::AMIL_S60QC_JUNDIAI,
            Planos::AMIL_S60QC_SP, Planos::AMIL_S75QC
        ]
    ];
    protected array $acomodacaoCodigo = [
        Acomodacao::ACOMODACAO_INDIVIDUAL => 9,
        Acomodacao::ACOMODACAO_COLETIVA   => 8
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!array_key_exists($this->regiaoSelecionada, $this->planos)) {
            return null;
        } elseif (!in_array($this->planoSelecionado, $this->planos[$this->regiaoSelecionada])) {
            return null;
        } elseif (
            !in_array($this->planoSelecionado, $this->acomodacoes[Acomodacao::ACOMODACAO_INDIVIDUAL])
            && !in_array($this->planoSelecionado, $this->acomodacoes[Acomodacao::ACOMODACAO_COLETIVA])
        ) {
            return null;
        } elseif (
            in_array($this->planoSelecionado, $this->acomodacoes[Acomodacao::ACOMODACAO_INDIVIDUAL])
        ) {
            return $this->acomodacaoCodigo[Acomodacao::ACOMODACAO_INDIVIDUAL];
        }
        return $this->acomodacaoCodigo[Acomodacao::ACOMODACAO_COLETIVA];
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
            $this->idade = $this->pegarIdade($dataNascimento);
        }

        if ($this->idade <= 18) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 342.78],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 370.20],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 512.96],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 548.84],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 564.79],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 604.34],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 628.83],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 635.07]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 158.93],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 293.05],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 225.35],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 243.38],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 325.28],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 333.71],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 370.44],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 412.19],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 417.37],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 421.52]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 177.42],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 144.63],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 309.50],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 334.27],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 384.88],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 427.24],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 420.72],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 467.02],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 520.51],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 620.97],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 627.14]
                ]
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 401.07],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 433.14],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 600.16],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 642.15],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 660.79],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 707.07],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 735.73],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 743.04]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 215.81],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 342.88],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 263.66],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 284.76],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 380.57],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 390.44],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 433.41],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 482.26],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 488.33],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 493.18]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 240.92],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 196.40],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 362.12],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 391.09],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 450.30],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 499.87],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 492.25],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 546.40],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 609.00],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 726.54],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 733.76]
                ]
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 489.29],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 528.44],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 732.18],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 783.43],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 806.18],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 862.63],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 897.60],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 906.50]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 253.34],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 418.30],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 321.67],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 347.40],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 464.31],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 476.36],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 528.76],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 588.36],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 595.77],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 601.68]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 282.83],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 230.54],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 441.78],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 477.11],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 549.37],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 609.83],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 600.53],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 666.61],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 742.97],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 886.38],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 895.20]
                ]
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 587.15],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 634.12],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 878.63],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 940.10],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 967.41],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1035.16],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1077.11],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1087.82]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 253.34],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 501.96],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 386.00],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 416.88],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 557.17],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 571.62],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 634.53],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 706.04],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 714.91],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 722.02]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 282.83],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 230.54],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 530.13],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 572.54],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 659.24],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 731.79],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 720.65],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 799.94],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 891.57],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1063.66],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1074.22]
                ]
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 616.51],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 665.83],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 922.56],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 987.10],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 1015.79],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1086.92],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1130.96],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1142.20]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 253.34],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 527.06],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 405.30],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 437.73],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 585.03],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 600.20],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 666.24],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 741.34],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 750.66],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 758.12]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 282.83],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 230.54],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 556.64],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 601.18],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 692.22],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 768.37],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 756.69],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 839.93],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 936.15],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1116.85],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1127.93]
                ]
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 678.17],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 732.41],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 1014.81],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1085.83],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 1117.36],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1195.61],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1244.07],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1256.42]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 282.98],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 579.75],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 445.84],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 481.50],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 643.53],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 660.23],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 732.86],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 815.47],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 825.73],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 833.93]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 315.93],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 257.52],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 612.30],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 661.28],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 761.42],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 845.21],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 832.35],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 923.93],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1029.76],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1228.52],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1240.73]
                ]
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 847.69],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 915.52],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 1268.52],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1357.27],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 1396.70],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1494.52],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1555.08],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1570.52]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 390.79],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 724.71],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 557.29],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 601.88],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 804.42],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 825.28],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 916.08],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1019.34],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1032.17],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1042.42]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 436.28],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 355.65],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 765.38],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 826.62],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 951.77],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1056.52],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 1040.44],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1154.91],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1287.20],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1535.66],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1550.93]
                ]
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 932.47],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1007.06],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 1395.37],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1492.99],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 1536.37],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1643.95],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1710.58],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1727.58]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 466.60],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 797.18],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 613.02],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 662.07],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 884.86],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 907.81],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1007.70],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1121.27],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1135.37],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1146.65]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 520.92],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 424.63],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 841.92],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 909.27],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 1046.96],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1162.17],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 1144.48],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1270.41],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1415.92],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1689.22],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1706.00]
                ]
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 1165.58],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1258.83],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 1744.21],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1866.24],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 1920.47],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2054.94],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2138.24],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2159.47]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 670.98],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 996.47],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 766.28],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 827.58],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1106.07],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 1134.76],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1259.61],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1401.59],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1419.21],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1433.32]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 749.08],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 610.63],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 1052.39],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1136.58],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 1308.69],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1452.71],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 1430.61],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1588.01],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1769.90],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2111.53],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2132.51]
                ]
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Regioes::BRASILIA       => [
                    Planos::AMIL_S80QC  => [Acomodacao::ACOMODACAO_COLETIVA => 2039.77],
                    Planos::AMIL_S80QP  => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2202.95],
                    Planos::AMIL_S380QC => [Acomodacao::ACOMODACAO_COLETIVA => 3052.38],
                    Planos::AMIL_S380QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3265.93],
                    Planos::AMIL_S450QC => [Acomodacao::ACOMODACAO_COLETIVA => 3360.81],
                    Planos::AMIL_S450QP => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3596.16],
                    Planos::AMIL_S750R1 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3741.91],
                    Planos::AMIL_S750R2 => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3779.08]
                ],
                Regioes::RIO_DE_JANEIRO => [
                    Planos::AMIL_S60QC_RJ => [Acomodacao::ACOMODACAO_COLETIVA => 951.47],
                    Planos::AMIL_S380QC   => [Acomodacao::ACOMODACAO_COLETIVA => 1743.81],
                    Planos::AMIL_S75QC    => [Acomodacao::ACOMODACAO_COLETIVA => 1340.99],
                    Planos::AMIL_S75QP    => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1448.27],
                    Planos::AMIL_S380QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1935.62],
                    Planos::AMIL_S450QC   => [Acomodacao::ACOMODACAO_COLETIVA => 1985.83],
                    Planos::AMIL_S450QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2204.31],
                    Planos::AMIL_S580QP   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2452.78],
                    Planos::AMIL_S750R1   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2483.64],
                    Planos::AMIL_S750R2   => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2508.31]
                ],
                Regioes::SAO_PAULO      => [
                    Planos::AMIL_S60QC_JUNDIAI => [Acomodacao::ACOMODACAO_COLETIVA => 1062.19],
                    Planos::AMIL_S60QC_SP      => [Acomodacao::ACOMODACAO_COLETIVA => 865.87],
                    Planos::AMIL_S80QC         => [Acomodacao::ACOMODACAO_COLETIVA => 1841.70],
                    Planos::AMIL_S80QP         => [Acomodacao::ACOMODACAO_INDIVIDUAL => 1989.03],
                    Planos::AMIL_S380QC        => [Acomodacao::ACOMODACAO_COLETIVA => 2290.24],
                    Planos::AMIL_S380QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2542.24],
                    Planos::AMIL_S450QC        => [Acomodacao::ACOMODACAO_COLETIVA => 2503.55],
                    Planos::AMIL_S450QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 2779.01],
                    Planos::AMIL_S580QP        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3097.33],
                    Planos::AMIL_S750R1        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3695.19],
                    Planos::AMIL_S750R2        => [Acomodacao::ACOMODACAO_INDIVIDUAL => 3731.88]
                ]
            ];
        }

        if (!array_key_exists($this->regiaoSelecionada, $this->valores)) {
            return null;
        } elseif (!array_key_exists($this->planoSelecionado, $this->valores[$this->regiaoSelecionada])) {
            return null;
        } elseif (
            !array_key_exists(
                $this->acomodacaoSelecionada,
                $this->valores[$this->regiaoSelecionada][$this->planoSelecionado]
            )
        ) {
            return null;
        }
        return $this->valores[$this->regiaoSelecionada][$this->planoSelecionado][$this->acomodacaoSelecionada];
    }

    /**
     * @return array Planos disponíveis na região
     */
    public function pegarPlanos(bool $semNomes = true): array
    {
        if ($semNomes) {
            return $this->planos;
        }
        $Planos = new Planos();
        $planosRetorno = [];
        foreach ($this->planos as $regiao => $planos) {
            foreach ($planos as $plano) {
                $planosRetorno[$regiao][$plano] = $Planos->nome($plano);
            }
        }
        return $planosRetorno;
    }

    /**
     * @param bool $all Pegar todas as acomodações independente do plano selecionado
     *
     * @return array|string Acomodações disponíveis no plano
     */
    public function pegarAcomodacoes(bool $all = false): array|string
    {
        if ($all) {
            return array_keys($this->acomodacoes);
        } elseif (
            in_array($this->planoSelecionado, $this->acomodacoes[Acomodacao::ACOMODACAO_INDIVIDUAL])
        ) {
            return Acomodacao::ACOMODACAO_INDIVIDUAL;
        } elseif (
            in_array($this->planoSelecionado, $this->acomodacoes[Acomodacao::ACOMODACAO_COLETIVA])
        ) {
            return Acomodacao::ACOMODACAO_COLETIVA;
        }
        return '';
    }

    /**
     * @return array Regiões disponíveis
     */
    public function pegarRegioes(): array
    {
        return (new Regioes())->select();
    }
}
