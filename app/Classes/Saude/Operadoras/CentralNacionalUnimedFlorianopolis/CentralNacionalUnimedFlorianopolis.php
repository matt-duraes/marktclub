<?php

namespace App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis;

use App\Classes\Saude\Operadoras\AbstractOperadora;
use App\Classes\Saude\Operadoras\Amil\Regioes;
use Exception;
use Modules\Data;

class CentralNacionalUnimedFlorianopolis extends AbstractOperadora
{
    private const ACOMODACAO_ENFERMARIA = 'enfermaria';
    private const ACOMODACAO_APARTAMENTO = 'apartamento';
    private const ACOMODACAO_ENFERMARIA_30 = 'enfermaria-30';
    private const ACOMODACAO_ENFERMARIA_50 = 'enfermaria-50';

    protected array $planos = [
        Planos::REGIONAL, Planos::ESTADUAL, Planos::NACIONAL
    ];
    protected array $acomodacoes = [
        Planos::REGIONAL => [
            self::ACOMODACAO_ENFERMARIA_50,
            self::ACOMODACAO_ENFERMARIA_30
        ],
        Planos::ESTADUAL => [
            self::ACOMODACAO_ENFERMARIA,
            self::ACOMODACAO_APARTAMENTO
        ],
        Planos::NACIONAL => [
            self::ACOMODACAO_ENFERMARIA,
            self::ACOMODACAO_APARTAMENTO
        ]
    ];
    protected array $acomodacaoCodigo = [
        self::ACOMODACAO_ENFERMARIA    => 1,
        self::ACOMODACAO_APARTAMENTO   => 2,
        self::ACOMODACAO_ENFERMARIA_50 => 6,
        self::ACOMODACAO_ENFERMARIA_30 => 7
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!in_array($this->planoSelecionado, $this->planos)) {
            return null;
        } elseif (!in_array($this->acomodacaoSelecionada, $this->acomodacoes[$this->planoSelecionado])) {
            return null;
        }
        return $this->acomodacaoCodigo[$this->acomodacaoSelecionada];
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
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 272.11,
                    self::ACOMODACAO_ENFERMARIA_50 => 225.52
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 273.45,
                    self::ACOMODACAO_APARTAMENTO => 368.39
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 321.03,
                    self::ACOMODACAO_APARTAMENTO => 407.17
                ]
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 329.25,
                    self::ACOMODACAO_ENFERMARIA_50 => 272.89
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 330.87,
                    self::ACOMODACAO_APARTAMENTO => 445.75
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 388.42,
                    self::ACOMODACAO_APARTAMENTO => 492.68
                ]
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 395.11,
                    self::ACOMODACAO_ENFERMARIA_50 => 327.48
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 397.05,
                    self::ACOMODACAO_APARTAMENTO => 534.90
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 466.11,
                    self::ACOMODACAO_APARTAMENTO => 591.20
                ]
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 462.28,
                    self::ACOMODACAO_ENFERMARIA_50 => 383.15
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 464.55,
                    self::ACOMODACAO_APARTAMENTO => 625.83
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 545.38,
                    self::ACOMODACAO_APARTAMENTO => 691.70
                ]
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 545.48,
                    self::ACOMODACAO_ENFERMARIA_50 => 452.11
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 548.17,
                    self::ACOMODACAO_APARTAMENTO => 738.49
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 643.53,
                    self::ACOMODACAO_APARTAMENTO => 816.21
                ]
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 621.84,
                    self::ACOMODACAO_ENFERMARIA_50 => 515.39
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 624.91,
                    self::ACOMODACAO_APARTAMENTO => 841.88
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 733.62,
                    self::ACOMODACAO_APARTAMENTO => 930.49
                ]
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 702.69,
                    self::ACOMODACAO_ENFERMARIA_50 => 582.43
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 706.15,
                    self::ACOMODACAO_APARTAMENTO => 951.32
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 828.98,
                    self::ACOMODACAO_APARTAMENTO => 1051.46
                ]
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 871.32,
                    self::ACOMODACAO_ENFERMARIA_50 => 722.19
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 875.62,
                    self::ACOMODACAO_APARTAMENTO => 1179.62
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 1027.94,
                    self::ACOMODACAO_APARTAMENTO => 1303.78
                ]
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 1115.29,
                    self::ACOMODACAO_ENFERMARIA_50 => 924.40
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 1120.81,
                    self::ACOMODACAO_APARTAMENTO => 1509.92
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 1315.77,
                    self::ACOMODACAO_APARTAMENTO => 1668.84
                ]
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Planos::REGIONAL => [
                    self::ACOMODACAO_ENFERMARIA_30 => 1594.86,
                    self::ACOMODACAO_ENFERMARIA_50 => 1321.90
                ],
                Planos::ESTADUAL => [
                    self::ACOMODACAO_ENFERMARIA  => 1602.74,
                    self::ACOMODACAO_APARTAMENTO => 2159.20
                ],
                Planos::NACIONAL => [
                    self::ACOMODACAO_ENFERMARIA  => 1881.57,
                    self::ACOMODACAO_APARTAMENTO => 2386.45
                ]
            ];
        }

        if (!array_key_exists($this->planoSelecionado, $this->valores)) {
            return null;
        } elseif (!array_key_exists($this->acomodacaoSelecionada, $this->valores[$this->planoSelecionado])) {
            return null;
        }
        return $this->valores[$this->planoSelecionado][$this->acomodacaoSelecionada];
    }

    /**
     * @param bool $all Pegar todas as acomodações independente do plano selecionado
     *
     * @return array|string Acomodações disponíveis no plano
     */
    public function pegarAcomodacoes(bool $all = false): array|string
    {
        if ($all) {
            return array_keys($this->acomodacaoCodigo);
        }
        return $this->acomodacoes[$this->planoSelecionado];
    }

    /**
     * @return array Regiões disponíveis
     */
    public function pegarRegioes(): array
    {
        return (new Regioes())->select();
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
}
