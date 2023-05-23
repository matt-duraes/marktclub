<?php

namespace App\Models\Api\SolicitacaoSaude;

use App\Classes\Saude\Helper;
use App\Classes\Saude\Localizacao;
use App\Classes\Saude\Operadora;
use App\Classes\Saude\Status;
use App\Classes\Saude\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\ValidarHelper;
use Http\Request;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

/**
 * @property $uuid
 * @property $data_nascimento
 * @property $quantidade_dependentes
 * @property $operadora
 * @property $acomodacao
 * @property $regiao
 * @property $valor
 * @property $tipo
 * @property $status
 */
class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    private const TABELA_SOLICITACAO_SAUDE = '';

    protected string $ormTabela = self::TABELA_SOLICITACAO_SAUDE;
    protected array $ormSalvar = [
        'uuid' => 'cod',
        'empresa', 'usuario', 'data_nascimento', 'quantidade_dependentes',
        'operadora', 'acomodacao', 'regiao', 'valor', 'tipo', 'status'
    ];
    protected string $uuid;
    protected Data $data_nascimento;
    protected int $quantidade_dependentes;
    protected Operadora $operadora;
    protected int $acomodacao;
    protected Localizacao $regiao;
    protected Dinheiro $valor;
    protected Tipo $tipo;
    protected Status $status;

    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $ValidarHelper = new ValidarHelper();

        $this->uuid = uuid();
        $this->data_nascimento = new Data($this->request->get('data_nascimento'));
        $this->operadora = new Operadora($this->request->get('operadora'));
        $this->regiao = new Localizacao($this->request->get('regiao'));
        $this->tipo = new Tipo($this->request->get('tipo'));
        $this->status = new Status(Status::REGISTRADO);

        if (
            $this->operadora->valido()
            && in_array($this->operadora->indice(), ['amil', 'central_nacional_unimed'], true)
        ) {
            if (!$this->tipo->valido()) {
                mensagemErro('Tipo inválido', 'Tipo não corresponde ao aceitável', 404);
            } elseif (!$this->regiao->valido()) {
                mensagemErro(
                    'Região inválida',
                    'Região não atendida ou não corresponde ao aceitável',
                    404
                );
            }
        }

        $ValidarHelper
            ->valor($this->request->get('acomodacao'), 'Acomodação')
            ->inArray(array_keys($this->pegarAcomodacao()))
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->get('titular'), 'Titular')
            ->date()
            ->obrigatorio()
            ->vazio();

        $dependentes = [];
        if (!empty($this->request->get('dependente'))) {
            $dependentes = explode(',', $this->request->get('dependente'));
            $this->quantidade_dependentes = count($dependentes);

            $contador = 1;
            for ($i = 0; $i <= $this->quantidade_dependentes; $i++) {
                $ValidarHelper
                    ->valor($dependentes[$i], 'Dependente ' . $contador)
                    ->date()
                    ->obrigatorio()
                    ->vazio();
                $contador++;
            }
        }

        if (!$ValidarHelper->valido()) {
            mensagemErro(
                'Informações inválidas',
                'Não conseguimos validar algumas informações.',
                404
            );
        }

        $valorTotal = $this->simularValor();
        if ($this->quantidade_dependentes > 0) {
            for ($i = 0; $i <= $this->quantidade_dependentes; $i++) {
                $valorTotal = $valorTotal + $this->simularValor($dependentes[$i]);
            }
        }

        $this->acomodacao = $this->pegarAcomodacao()[$this->request->get('acomodacao')];
        $this->valor = new Dinheiro((string)$valorTotal);
    }

    /**
     * @return string[]
     */
    private function pegarAcomodacao(): array
    {
        $tipo = $this->tipo->indice();
        return match ($this->operadora->indice()) {
            'unimed' => Helper::ACOMODACAO['unimed'],
            'unimed_seguro' => Helper::ACOMODACAO['unimed_seguro'],
            'central_nacional_unimed' => Helper::ACOMODACAO['central_nacional_unimed'][$tipo],
            'central_nacional_unimed_floripa' => Helper::ACOMODACAO['central_nacional_unimed_floripa'][$tipo],
            'amil' => Helper::ACOMODACAO['amil'][$tipo],
            default => []
        };
    }

    /**
     * @param  string|null  $dataNascimento
     *
     * @return float
     */
    private function simularValor(string $dataNascimento = null): float
    {
        $idade = $this->pegarIdadePelaDataNascimento($dataNascimento);

        if ($this->operadora->indice() === 'unimed') {
            $valor = $this->simularValorUnimed($idade);
        } elseif ($this->operadora->indice() === 'unimed_seguro') {
            $valor = $this->simularValorUnimedSeguro($idade);
        } elseif ($this->operadora->indice() === 'central_nacional_unimed') {
            $valor = $this->simularValorCentralNacionalUnimed($idade);
        } elseif ($this->operadora->indice() === 'central_nacional_unimed_floripa') {
            $valor = $this->simularValorCentralNacionalUnimedFloripa($idade);
        } elseif ($this->operadora->indice() === 'amil') {
            $valor = $this->simularValorAmil($idade);
        } else {
            $valor = 0;
        }

        return $valor;
    }

    /**
     * @param  string|null  $dataNascimento
     *
     * @return int
     */
    private function pegarIdadePelaDataNascimento(string $dataNascimento = null): int
    {
        $dataNascimento = ($dataNascimento === null) ? $this->data_nascimento->date() : $dataNascimento;
        [$ano, $mes, $dia] = explode('-', $dataNascimento);
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $dataNascimento = mktime(0, 0, 0, $mes, $dia, $ano);
        return floor((((($hoje - $dataNascimento) / 60) / 60) / 24) / 365.25);
    }

    /**
     * @param  int  $idade
     *
     * @return float
     */
    private function simularValorUnimed(int $idade): float
    {
        if ($idade <= 18) {
            $valores = ['enfermaria' => 235.92, 'apartamento' => 284.18];
        } elseif ($idade >= 19 && $idade <= 23) {
            $valores = ['enfermaria' => 254.35, 'apartamento' => 306.12];
        } elseif ($idade >= 24 && $idade <= 28) {
            $valores = ['enfermaria' => 274.69, 'apartamento' => 330.60];
        } elseif ($idade >= 29 && $idade <= 33) {
            $valores = ['enfermaria' => 316.75, 'apartamento' => 376.56];
        } elseif ($idade >= 34 && $idade <= 38) {
            $valores = ['enfermaria' => 342.09, 'apartamento' => 406.66];
        } elseif ($idade >= 39 && $idade <= 43) {
            $valores = ['enfermaria' => 362.78, 'apartamento' => 435.70];
        } elseif ($idade >= 44 && $idade <= 48) {
            $valores = ['enfermaria' => 578.07, 'apartamento' => 696.47];
        } elseif ($idade >= 49 && $idade <= 53) {
            $valores = ['enfermaria' => 602.91, 'apartamento' => 726.38];
        } elseif ($idade >= 54 && $idade <= 58) {
            $valores = ['enfermaria' => 627.69, 'apartamento' => 756.30];
        } elseif ($idade >= 59) {
            $valores = ['enfermaria' => 1307.28, 'apartamento' => 1496.77];
        } else {
            $valores = 0;
        }

        if (!array_key_exists($this->acomodacao, $valores)) {
            return 0;
        } elseif (is_numeric($valores)) {
            return $valores;
        }

        return $valores[$this->acomodacao];
    }

    /**
     * @param  int  $idade
     *
     * @return float
     */
    private function simularValorUnimedSeguro(int $idade): float
    {
        if ($idade <= 18) {
            $valores = ['basico' => 302.96, 'pratico' => 402.57, 'versatil' => 484.41];
        } elseif ($idade >= 19 && $idade <= 23) {
            $valores = ['basico' => 365.27, 'pratico' => 488.18, 'versatil' => 577.25];
        } elseif ($idade >= 24 && $idade <= 28) {
            $valores = ['basico' => 421.29, 'pratico' => 565.10, 'versatil' => 667.54];
        } elseif ($idade >= 29 && $idade <= 33) {
            $valores = ['basico' => 479.93, 'pratico' => 645.61, 'versatil' => 767.32];
        } elseif ($idade >= 34 && $idade <= 38) {
            $valores = ['basico' => 552.27, 'pratico' => 742.55, 'versatil' => 882.94];
        } elseif ($idade >= 39 && $idade <= 43) {
            $valores = ['basico' => 650.44, 'pratico' => 865.98, 'versatil' => 1039.89];
        } elseif ($idade >= 44 && $idade <= 48) {
            $valores = ['basico' => 758.29, 'pratico' => 1003.56, 'versatil' => 1212.32];
        } elseif ($idade >= 49 && $idade <= 53) {
            $valores = ['basico' => 1016.58, 'pratico' => 1354.63, 'versatil' => 1640.57];
        } elseif ($idade >= 54 && $idade <= 58) {
            $valores = ['basico' => 1372.30, 'pratico' => 1828.17, 'versatil' => 2218.28];
        } elseif ($idade >= 59) {
            $valores = ['basico' => 1805.72, 'pratico' => 2415.49, 'versatil' => 2901.35];
        } else {
            $valores = 0;
        }

        if (!array_key_exists($this->acomodacao, $valores)) {
            return 0;
        } elseif (is_numeric($valores)) {
            return $valores;
        }

        return $valores[$this->acomodacao];
    }

    /**
     * @param  int  $idade
     *
     * @return float
     */
    private function simularValorCentralNacionalUnimed(int $idade): float
    {
        if ($idade <= 18) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 225.09],
                    'estilo-nacional'    => ['enfermaria' => 277.89, 'apartamento' => 341.82],
                    'absoluto'           => ['apartamento' => 376.56],
                    'superior-nacional'  => ['apartamento' => 484.86],
                    'exclusivo-nacional' => ['apartamento' => 1240.57]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 224.37],
                    'estilo-nacional'    => ['enfermaria' => 277.00, 'apartamento' => 340.71],
                    'absoluto'           => ['apartamento' => 375.33],
                    'superior-nacional'  => ['apartamento' => 483.30],
                    'exclusivo-nacional' => ['apartamento' => 1224.08]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 229.45],
                    'estilo-nacional'    => ['enfermaria' => 266.66, 'apartamento' => 314.23],
                    'absoluto'           => ['apartamento' => 383.83],
                    'superior-nacional'  => ['apartamento' => 494.23],
                    'exclusivo-nacional' => ['apartamento' => 1254.93]
                ]
            ];
        } elseif ($idade >= 19 && $idade <= 23) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 288.13],
                    'estilo-nacional'    => ['enfermaria' => 355.71, 'apartamento' => 437.53],
                    'absoluto'           => ['apartamento' => 481.99],
                    'superior-nacional'  => ['apartamento' => 620.63],
                    'exclusivo-nacional' => ['apartamento' => 1587.97]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 287.20],
                    'estilo-nacional'    => ['enfermaria' => 354.57, 'apartamento' => 436.12],
                    'absoluto'           => ['apartamento' => 480.43],
                    'superior-nacional'  => ['apartamento' => 618.63],
                    'exclusivo-nacional' => ['apartamento' => 1566.86]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 293.71],
                    'estilo-nacional'    => ['enfermaria' => 341.33, 'apartamento' => 402.22],
                    'absoluto'           => ['apartamento' => 491.31],
                    'superior-nacional'  => ['apartamento' => 632.63],
                    'exclusivo-nacional' => ['apartamento' => 1606.35]
                ]
            ];
        } elseif ($idade >= 24 && $idade <= 28) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 306.11],
                    'estilo-nacional'    => ['enfermaria' => 377.92, 'apartamento' => 464.84],
                    'absoluto'           => ['apartamento' => 512.08],
                    'superior-nacional'  => ['apartamento' => 659.37],
                    'exclusivo-nacional' => ['apartamento' => 1687.07]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 305.12],
                    'estilo-nacional'    => ['enfermaria' => 376.70, 'apartamento' => 463.34],
                    'absoluto'           => ['apartamento' => 510.43],
                    'superior-nacional'  => ['apartamento' => 657.25],
                    'exclusivo-nacional' => ['apartamento' => 1664.66]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 312.03],
                    'estilo-nacional'    => ['enfermaria' => 362.62, 'apartamento' => 427.33],
                    'absoluto'           => ['apartamento' => 521.98],
                    'superior-nacional'  => ['apartamento' => 672.11],
                    'exclusivo-nacional' => ['apartamento' => 1706.61]
                ]
            ];
        } elseif ($idade >= 29 && $idade <= 33) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 315.09],
                    'estilo-nacional'    => ['enfermaria' => 389.00, 'apartamento' => 478.47],
                    'absoluto'           => ['apartamento' => 527.10],
                    'superior-nacional'  => ['apartamento' => 678.70],
                    'exclusivo-nacional' => ['apartamento' => 1736.55]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 314.08],
                    'estilo-nacional'    => ['enfermaria' => 387.75, 'apartamento' => 476.93],
                    'absoluto'           => ['apartamento' => 525.39],
                    'superior-nacional'  => ['apartamento' => 676.52],
                    'exclusivo-nacional' => ['apartamento' => 1713.48]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 321.18],
                    'estilo-nacional'    => ['enfermaria' => 373.27, 'apartamento' => 439.86],
                    'absoluto'           => ['apartamento' => 537.29],
                    'superior-nacional'  => ['apartamento' => 691.83],
                    'exclusivo-nacional' => ['apartamento' => 1756.66]
                ]
            ];
        } elseif ($idade >= 34 && $idade <= 38) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 344.36],
                    'estilo-nacional'    => ['enfermaria' => 425.14, 'apartamento' => 522.92],
                    'absoluto'           => ['apartamento' => 576.06],
                    'superior-nacional'  => ['apartamento' => 741.75],
                    'exclusivo-nacional' => ['apartamento' => 1897.88]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 343.25],
                    'estilo-nacional'    => ['enfermaria' => 423.77, 'apartamento' => 521.23],
                    'absoluto'           => ['apartamento' => 574.20],
                    'superior-nacional'  => ['apartamento' => 739.36],
                    'exclusivo-nacional' => ['apartamento' => 1872.66]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 351.03],
                    'estilo-nacional'    => ['enfermaria' => 407.94, 'apartamento' => 480.72],
                    'absoluto'           => ['apartamento' => 587.20],
                    'superior-nacional'  => ['apartamento' => 756.10],
                    'exclusivo-nacional' => ['apartamento' => 1919.86]
                ]
            ];
        } elseif ($idade >= 39 && $idade <= 43) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 393.87],
                    'estilo-nacional'    => ['enfermaria' => 486.26, 'apartamento' => 598.09],
                    'absoluto'           => ['apartamento' => 658.87],
                    'superior-nacional'  => ['apartamento' => 848.4],
                    'exclusivo-nacional' => ['apartamento' => 2170.72]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 392.59],
                    'estilo-nacional'    => ['enfermaria' => 484.69, 'apartamento' => 596.17],
                    'absoluto'           => ['apartamento' => 656.75],
                    'superior-nacional'  => ['apartamento' => 845.66],
                    'exclusivo-nacional' => ['apartamento' => 2141.86]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 401.49],
                    'estilo-nacional'    => ['enfermaria' => 466.59, 'apartamento' => 549.84],
                    'absoluto'           => ['apartamento' => 671.62],
                    'superior-nacional'  => ['apartamento' => 864.79],
                    'exclusivo-nacional' => ['apartamento' => 2195.86]
                ]
            ];
        } elseif ($idade >= 44 && $idade <= 48) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 551.39],
                    'estilo-nacional'    => ['enfermaria' => 680.73, 'apartamento' => 837.3],
                    'absoluto'           => ['apartamento' => 922.39],
                    'superior-nacional'  => ['apartamento' => 1187.7],
                    'exclusivo-nacional' => ['apartamento' => 3038.89]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 549.61],
                    'estilo-nacional'    => ['enfermaria' => 678.54, 'apartamento' => 834.6],
                    'absoluto'           => ['apartamento' => 919.41],
                    'superior-nacional'  => ['apartamento' => 1183.87],
                    'exclusivo-nacional' => ['apartamento' => 2998.49]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 562.06],
                    'estilo-nacional'    => ['enfermaria' => 653.19, 'apartamento' => 769.73],
                    'absoluto'           => ['apartamento' => 940.23],
                    'superior-nacional'  => ['apartamento' => 1210.67],
                    'exclusivo-nacional' => ['apartamento' => 3074.07]
                ]
            ];
        } elseif ($idade >= 49 && $idade <= 53) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 738.22],
                    'estilo-nacional'    => ['enfermaria' => 911.39, 'apartamento' => 1121.01],
                    'absoluto'           => ['apartamento' => 1234.92],
                    'superior-nacional'  => ['apartamento' => 1590.13],
                    'exclusivo-nacional' => ['apartamento' => 4068.55]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 735.84],
                    'estilo-nacional'    => ['enfermaria' => 908.44, 'apartamento' => 1117.38],
                    'absoluto'           => ['apartamento' => 1230.93],
                    'superior-nacional'  => ['apartamento' => 1584.99],
                    'exclusivo-nacional' => ['apartamento' => 4014.47]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 752.50],
                    'estilo-nacional'    => ['enfermaria' => 874.51, 'apartamento' => 1030.54],
                    'absoluto'           => ['apartamento' => 1258.80],
                    'superior-nacional'  => ['apartamento' => 1620.88],
                    'exclusivo-nacional' => ['apartamento' => 4115.66]
                ]
            ];
        } elseif ($idade >= 54 && $idade <= 58) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 828.29],
                    'estilo-nacional'    => ['enfermaria' => 1022.58, 'apartamento' => 1257.77],
                    'absoluto'           => ['apartamento' => 1385.59],
                    'superior-nacional'  => ['apartamento' => 1784.13],
                    'exclusivo-nacional' => ['apartamento' => 4564.92]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 825.62],
                    'estilo-nacional'    => ['enfermaria' => 1019.27, 'apartamento' => 1253.71],
                    'absoluto'           => ['apartamento' => 1381.12],
                    'superior-nacional'  => ['apartamento' => 1778.36],
                    'exclusivo-nacional' => ['apartamento' => 4504.25]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 844.31],
                    'estilo-nacional'    => ['enfermaria' => 981.20, 'apartamento' => 1156.28],
                    'absoluto'           => ['apartamento' => 1412.38],
                    'superior-nacional'  => ['apartamento' => 1818.62],
                    'exclusivo-nacional' => ['apartamento' => 4617.77]
                ]
            ];
        } elseif ($idade >= 59) {
            $valores = [
                'brasilia'  => [
                    'classico-regional'  => ['enfermaria' => 1350.43],
                    'estilo-nacional'    => ['enfermaria' => 1667.19, 'apartamento' => 2050.64],
                    'absoluto'           => ['apartamento' => 2259.03],
                    'superior-nacional'  => ['apartamento' => 2908.81],
                    'exclusivo-nacional' => ['apartamento' => 7442.54]
                ],
                'salvador'  => [
                    'classico-regional'  => ['enfermaria' => 1346.06],
                    'estilo-nacional'    => ['enfermaria' => 1661.81, 'apartamento' => 2044.01],
                    'absoluto'           => ['apartamento' => 2251.73],
                    'superior-nacional'  => ['apartamento' => 2899.41],
                    'exclusivo-nacional' => ['apartamento' => 7343.61]
                ],
                'sao-paulo' => [
                    'classico-regional'  => ['enfermaria' => 1376.53],
                    'estilo-nacional'    => ['enfermaria' => 1599.74, 'apartamento' => 1885.17],
                    'absoluto'           => ['apartamento' => 2302.71],
                    'superior-nacional'  => ['apartamento' => 2965.05],
                    'exclusivo-nacional' => ['apartamento' => 7528.71]
                ]
            ];
        } else {
            $valores = 0;
        }

        if (
            !array_key_exists($this->regiao->indice(), $valores)
            || !array_key_exists($this->tipo->indice(), $valores[$this->regiao->indice()])
            || !array_key_exists($this->acomodacao, $valores[$this->regiao->indice()][$this->tipo->indice()])
        ) {
            return 0;
        } elseif (is_numeric($valores)) {
            return $valores;
        }

        return $valores[$this->regiao->indice()][$this->tipo->indice()][$this->acomodacao];
    }

    /**
     * @param  int  $idade
     *
     * @return float
     */
    private function simularValorCentralNacionalUnimedFloripa(int $idade): float
    {
        if ($idade <= 18) {
            $valores = [272.11, 225.52, 273.45, 368.39, 321.03, 407.17];
        } elseif ($idade >= 19 && $idade <= 23) {
            $valores = [329.25, 272.89, 330.87, 445.75, 388.42, 492.68];
        } elseif ($idade >= 24 && $idade <= 28) {
            $valores = [395.11, 327.48, 397.05, 534.90, 466.11, 591.20];
        } elseif ($idade >= 29 && $idade <= 33) {
            $valores = [462.28, 383.15, 464.55, 625.83, 545.38, 691.70];
        } elseif ($idade >= 34 && $idade <= 38) {
            $valores = [545.48, 452.11, 548.17, 738.49, 643.53, 816.21];
        } elseif ($idade >= 39 && $idade <= 43) {
            $valores = [621.84, 515.39, 624.91, 841.88, 733.62, 930.49];
        } elseif ($idade >= 44 && $idade <= 48) {
            $valores = [702.69, 582.43, 706.15, 951.32, 828.98, 1051.46];
        } elseif ($idade >= 49 && $idade <= 53) {
            $valores = [871.32, 722.19, 875.62, 1179.62, 1027.94, 1303.78];
        } elseif ($idade >= 54 && $idade <= 58) {
            $valores = [1115.29, 924.40, 1120.81, 1509.92, 1315.77, 1668.84];
        } elseif ($idade >= 59) {
            $valores = [1594.86, 1321.90, 1602.74, 2159.20, 1881.57, 2386.45];
        } else {
            $valores = 0;
        }

        $lista = [
            'regional' => ['enfermaria-30' => 0, 'enfermaria-50' => 1],
            'estadual' => ['enfermaria' => 2, 'apartamento' => 3],
            'nacional' => ['enfermaria' => 4, 'apartamento' => 5]
        ];

        if (
            !array_key_exists($this->tipo->indice(), $lista)
            || !array_key_exists($this->acomodacao, $lista[$this->tipo->indice()])
        ) {
            return 0;
        } elseif (is_numeric($valores)) {
            return $valores;
        }

        $index = $lista[$this->tipo->indice()][$this->acomodacao];
        return $valores[$index];
    }

    /**
     * @param  int  $idade
     *
     * @return float
     */
    private function simularValorAmil(int $idade): float
    {
        if ($idade <= 18) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 342.78],
                    'amil_s80qp'  => ['individual' => 370.20],
                    'amil_s380qc' => ['coletivo' => 512.96],
                    'amil_s380qp' => ['individual' => 548.84],
                    'amil_s450qc' => ['coletivo' => 564.79],
                    'amil_s450qp' => ['individual' => 604.34],
                    'amil_s750r1' => ['individual' => 628.83],
                    'amil_s750r2' => ['individual' => 635.07]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 158.93],
                    'amil_s380qc'   => ['coletivo' => 293.05],
                    'amil_s75qc'    => ['coletivo' => 225.35],
                    'amil_s75qp'    => ['individual' => 243.38],
                    'amil_s380qp'   => ['individual' => 325.28],
                    'amil_s450qc'   => ['coletivo' => 333.71],
                    'amil_s450qp'   => ['individual' => 370.44],
                    'amil_s580qp'   => ['individual' => 412.19],
                    'amil_s750r1'   => ['individual' => 417.37],
                    'amil_s750r2'   => ['individual' => 421.52]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 177.42],
                    'amil_s60qc_sp'      => ['coletivo' => 144.63],
                    'amil_s80qc'         => ['coletivo' => 309.50],
                    'amil_s80qp'         => ['individual' => 334.27],
                    'amil_s380qc'        => ['coletivo' => 384.88],
                    'amil_s380qp'        => ['individual' => 427.24],
                    'amil_s450qc'        => ['coletivo' => 420.72],
                    'amil_s450qp'        => ['individual' => 467.02],
                    'amil_s580qp'        => ['individual' => 520.51],
                    'amil_s750r1'        => ['individual' => 620.97],
                    'amil_s750r2'        => ['individual' => 627.14]
                ]
            ];
        } elseif ($idade >= 19 && $idade <= 23) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 401.07],
                    'amil_s80qp'  => ['individual' => 433.14],
                    'amil_s380qc' => ['coletivo' => 600.16],
                    'amil_s380qp' => ['individual' => 642.15],
                    'amil_s450qc' => ['coletivo' => 660.79],
                    'amil_s450qp' => ['individual' => 707.07],
                    'amil_s750r1' => ['individual' => 735.73],
                    'amil_s750r2' => ['individual' => 906.50]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 215.81],
                    'amil_s380qc'   => ['coletivo' => 342.88],
                    'amil_s75qc'    => ['coletivo' => 263.66],
                    'amil_s75qp'    => ['individual' => 284.76],
                    'amil_s380qp'   => ['individual' => 380.57],
                    'amil_s450qc'   => ['coletivo' => 390.44],
                    'amil_s450qp'   => ['individual' => 433.41],
                    'amil_s580qp'   => ['individual' => 482.26],
                    'amil_s750r1'   => ['individual' => 488.33],
                    'amil_s750r2'   => ['individual' => 493.18]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 240.92],
                    'amil_s60qc_sp'      => ['coletivo' => 196.40],
                    'amil_s80qc'         => ['coletivo' => 362.12],
                    'amil_s80qp'         => ['individual' => 391.09],
                    'amil_s380qc'        => ['coletivo' => 450.30],
                    'amil_s380qp'        => ['individual' => 499.87],
                    'amil_s450qc'        => ['coletivo' => 492.25],
                    'amil_s450qp'        => ['individual' => 546.40],
                    'amil_s580qp'        => ['individual' => 609.00],
                    'amil_s750r1'        => ['individual' => 726.54],
                    'amil_s750r2'        => ['individual' => 733.76]
                ]
            ];
        } elseif ($idade >= 24 && $idade <= 28) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 489.29],
                    'amil_s80qp'  => ['individual' => 528.44],
                    'amil_s380qc' => ['coletivo' => 732.18],
                    'amil_s380qp' => ['individual' => 783.43],
                    'amil_s450qc' => ['coletivo' => 806.18],
                    'amil_s450qp' => ['individual' => 862.63],
                    'amil_s750r1' => ['individual' => 897.60],
                    'amil_s750r2' => ['individual' => 906.50]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 253.34],
                    'amil_s380qc'   => ['coletivo' => 418.30],
                    'amil_s75qc'    => ['coletivo' => 321.67],
                    'amil_s75qp'    => ['individual' => 347.40],
                    'amil_s380qp'   => ['individual' => 464.31],
                    'amil_s450qc'   => ['coletivo' => 476.36],
                    'amil_s450qp'   => ['individual' => 528.76],
                    'amil_s580qp'   => ['individual' => 588.36],
                    'amil_s750r1'   => ['individual' => 595.77],
                    'amil_s750r2'   => ['individual' => 601.68]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 282.83],
                    'amil_s60qc_sp'      => ['coletivo' => 230, .4],
                    'amil_s80qc'         => ['coletivo' => 441.78],
                    'amil_s80qp'         => ['individual' => 477.11],
                    'amil_s380qc'        => ['coletivo' => 549.37],
                    'amil_s380qp'        => ['individual' => 609.83],
                    'amil_s450qc'        => ['coletivo' => 600.53],
                    'amil_s450qp'        => ['individual' => 666.61],
                    'amil_s580qp'        => ['individual' => 742, 97],
                    'amil_s750r1'        => ['individual' => 886.38],
                    'amil_s750r2'        => ['individual' => 895.20]
                ]
            ];
        } elseif ($idade >= 29 && $idade <= 33) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 587.15],
                    'amil_s80qp'  => ['individual' => 634.12],
                    'amil_s380qc' => ['coletivo' => 878.63],
                    'amil_s380qp' => ['individual' => 940.10],
                    'amil_s450qc' => ['coletivo' => 967.41],
                    'amil_s450qp' => ['individual' => 1035.16],
                    'amil_s750r1' => ['individual' => 1077.11],
                    'amil_s750r2' => ['individual' => 1087.82]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 253.34],
                    'amil_s380qc'   => ['coletivo' => 501.96],
                    'amil_s75qc'    => ['coletivo' => 386.00],
                    'amil_s75qp'    => ['individual' => 416.88],
                    'amil_s380qp'   => ['individual' => 557.17],
                    'amil_s450qc'   => ['coletivo' => 571.62],
                    'amil_s450qp'   => ['individual' => 634.53],
                    'amil_s580qp'   => ['individual' => 706.04],
                    'amil_s750r1'   => ['individual' => 714.91],
                    'amil_s750r2'   => ['individual' => 722.02]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 282.83],
                    'amil_s60qc_sp'      => ['coletivo' => 230.54],
                    'amil_s80qc'         => ['coletivo' => 530.13],
                    'amil_s80qp'         => ['individual' => 572.54],
                    'amil_s380qc'        => ['coletivo' => 659.24],
                    'amil_s380qp'        => ['individual' => 731.79],
                    'amil_s450qc'        => ['coletivo' => 720.65],
                    'amil_s450qp'        => ['individual' => 799.94],
                    'amil_s580qp'        => ['individual' => 891.57],
                    'amil_s750r1'        => ['individual' => 1063.66],
                    'amil_s750r2'        => ['individual' => 1074.22]
                ]
            ];
        } elseif ($idade >= 34 && $idade <= 38) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 616.51],
                    'amil_s80qp'  => ['individual' => 665.83],
                    'amil_s380qc' => ['coletivo' => 922.56],
                    'amil_s380qp' => ['individual' => 987.10],
                    'amil_s450qc' => ['coletivo' => 1015.79],
                    'amil_s450qp' => ['individual' => 1086.92],
                    'amil_s750r1' => ['individual' => 1130.96],
                    'amil_s750r2' => ['individual' => 1142.20]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 253.34],
                    'amil_s380qc'   => ['coletivo' => 527.06],
                    'amil_s75qc'    => ['coletivo' => 405.30],
                    'amil_s75qp'    => ['individual' => 437.73],
                    'amil_s380qp'   => ['individual' => 585.03],
                    'amil_s450qc'   => ['coletivo' => 600.20],
                    'amil_s450qp'   => ['individual' => 666.24],
                    'amil_s580qp'   => ['individual' => 741.34],
                    'amil_s750r1'   => ['individual' => 750.66],
                    'amil_s750r2'   => ['individual' => 758.12]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 282.83],
                    'amil_s60qc_sp'      => ['coletivo' => 230.54],
                    'amil_s80qc'         => ['coletivo' => 556.64],
                    'amil_s80qp'         => ['individual' => 601.18],
                    'amil_s380qc'        => ['coletivo' => 692.22],
                    'amil_s380qp'        => ['individual' => 768.37],
                    'amil_s450qc'        => ['coletivo' => 756.69],
                    'amil_s450qp'        => ['individual' => 839.93],
                    'amil_s580qp'        => ['individual' => 936.15],
                    'amil_s750r1'        => ['individual' => 1116.85],
                    'amil_s750r2'        => ['individual' => 1127.93]
                ]
            ];
        } elseif ($idade >= 39 && $idade <= 43) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 678.17],
                    'amil_s80qp'  => ['individual' => 732.41],
                    'amil_s380qc' => ['coletivo' => 1014.81],
                    'amil_s380qp' => ['individual' => 1085.83],
                    'amil_s450qc' => ['coletivo' => 1117.36],
                    'amil_s450qp' => ['individual' => 1195.61],
                    'amil_s750r1' => ['individual' => 1244.07],
                    'amil_s750r2' => ['individual' => 1256.42]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 282.98],
                    'amil_s380qc'   => ['coletivo' => 579.75],
                    'amil_s75qc'    => ['coletivo' => 445.84],
                    'amil_s75qp'    => ['individual' => 481.50],
                    'amil_s380qp'   => ['individual' => 643.53],
                    'amil_s450qc'   => ['coletivo' => 660.23],
                    'amil_s450qp'   => ['individual' => 732.86],
                    'amil_s580qp'   => ['individual' => 815.47],
                    'amil_s750r1'   => ['individual' => 825.73],
                    'amil_s750r2'   => ['individual' => 833.93]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 315.93],
                    'amil_s60qc_sp'      => ['coletivo' => 257.52],
                    'amil_s80qc'         => ['coletivo' => 612.30],
                    'amil_s80qp'         => ['individual' => 661.28],
                    'amil_s380qc'        => ['coletivo' => 761.42],
                    'amil_s380qp'        => ['individual' => 845.21],
                    'amil_s450qc'        => ['coletivo' => 832.35],
                    'amil_s450qp'        => ['individual' => 923.93],
                    'amil_s580qp'        => ['individual' => 1029.76],
                    'amil_s750r1'        => ['individual' => 1228.52],
                    'amil_s750r2'        => ['individual' => 1240.73]
                ]
            ];
        } elseif ($idade >= 44 && $idade <= 48) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 847.69],
                    'amil_s80qp'  => ['individual' => 915.52],
                    'amil_s380qc' => ['coletivo' => 1268.52],
                    'amil_s380qp' => ['individual' => 1357.27],
                    'amil_s450qc' => ['coletivo' => 1396.70],
                    'amil_s450qp' => ['individual' => 1494.52],
                    'amil_s750r1' => ['individual' => 1555.08],
                    'amil_s750r2' => ['individual' => 1570.52]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 390.79],
                    'amil_s380qc'   => ['coletivo' => 724.71],
                    'amil_s75qc'    => ['coletivo' => 557.29],
                    'amil_s75qp'    => ['individual' => 601.88],
                    'amil_s380qp'   => ['individual' => 804.42],
                    'amil_s450qc'   => ['coletivo' => 825.28],
                    'amil_s450qp'   => ['individual' => 916.08],
                    'amil_s580qp'   => ['individual' => 1019.34],
                    'amil_s750r1'   => ['individual' => 1032.17],
                    'amil_s750r2'   => ['individual' => 1042.42]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 436.28],
                    'amil_s60qc_sp'      => ['coletivo' => 355.65],
                    'amil_s80qc'         => ['coletivo' => 765.38],
                    'amil_s80qp'         => ['individual' => 826.62],
                    'amil_s380qc'        => ['coletivo' => 951.77],
                    'amil_s380qp'        => ['individual' => 1056.52],
                    'amil_s450qc'        => ['coletivo' => 1040.44],
                    'amil_s450qp'        => ['individual' => 1154.91],
                    'amil_s580qp'        => ['individual' => 1287.20],
                    'amil_s750r1'        => ['individual' => 1535.66],
                    'amil_s750r2'        => ['individual' => 1550.93]
                ]
            ];
        } elseif ($idade >= 49 && $idade <= 53) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 932.47],
                    'amil_s80qp'  => ['individual' => 1007.06],
                    'amil_s380qc' => ['coletivo' => 1395.37],
                    'amil_s380qp' => ['individual' => 1492.99],
                    'amil_s450qc' => ['coletivo' => 1536.37],
                    'amil_s450qp' => ['individual' => 1643.95],
                    'amil_s750r1' => ['individual' => 1710.58],
                    'amil_s750r2' => ['individual' => 1727.58]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 466.60],
                    'amil_s380qc'   => ['coletivo' => 797.18],
                    'amil_s75qc'    => ['coletivo' => 613.02],
                    'amil_s75qp'    => ['individual' => 662.07],
                    'amil_s380qp'   => ['individual' => 884.86],
                    'amil_s450qc'   => ['coletivo' => 907.81],
                    'amil_s450qp'   => ['individual' => 1007.70],
                    'amil_s580qp'   => ['individual' => 1121.27],
                    'amil_s750r1'   => ['individual' => 1135.37],
                    'amil_s750r2'   => ['individual' => 1146.65]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 520.92],
                    'amil_s60qc_sp'      => ['coletivo' => 424.63],
                    'amil_s80qc'         => ['coletivo' => 841.92],
                    'amil_s80qp'         => ['individual' => 909.27],
                    'amil_s380qc'        => ['coletivo' => 1046.96],
                    'amil_s380qp'        => ['individual' => 1162.17],
                    'amil_s450qc'        => ['coletivo' => 1144.48],
                    'amil_s450qp'        => ['individual' => 1270.41],
                    'amil_s580qp'        => ['individual' => 1415.92],
                    'amil_s750r1'        => ['individual' => 1689.22],
                    'amil_s750r2'        => ['individual' => 1706.00]
                ]
            ];
        } elseif ($idade >= 54 && $idade <= 58) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 1165.58],
                    'amil_s80qp'  => ['individual' => 1258.83],
                    'amil_s380qc' => ['coletivo' => 1744.21],
                    'amil_s380qp' => ['individual' => 1866.24],
                    'amil_s450qc' => ['coletivo' => 1920.47],
                    'amil_s450qp' => ['individual' => 2054.94],
                    'amil_s750r1' => ['individual' => 2138.24],
                    'amil_s750r2' => ['individual' => 2159.47]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 670.98],
                    'amil_s380qc'   => ['coletivo' => 996.47],
                    'amil_s75qc'    => ['coletivo' => 766.28],
                    'amil_s75qp'    => ['individual' => 827.58],
                    'amil_s380qp'   => ['individual' => 1106.07],
                    'amil_s450qc'   => ['coletivo' => 1134.76],
                    'amil_s450qp'   => ['individual' => 1259.61],
                    'amil_s580qp'   => ['individual' => 1401.59],
                    'amil_s750r1'   => ['individual' => 1419.21],
                    'amil_s750r2'   => ['individual' => 1433.32]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 749.08],
                    'amil_s60qc_sp'      => ['coletivo' => 610.63],
                    'amil_s80qc'         => ['coletivo' => 1052.39],
                    'amil_s80qp'         => ['individual' => 1136.58],
                    'amil_s380qc'        => ['coletivo' => 1308.69],
                    'amil_s380qp'        => ['individual' => 1452.71],
                    'amil_s450qc'        => ['coletivo' => 1430.61],
                    'amil_s450qp'        => ['individual' => 1588.01],
                    'amil_s580qp'        => ['individual' => 1769.90],
                    'amil_s750r1'        => ['individual' => 2111.53],
                    'amil_s750r2'        => ['individual' => 2132.51]
                ]
            ];
        } elseif ($idade >= 59) {
            $valores = [
                'brasilia'       => [
                    'amil_s80qc'  => ['coletivo' => 2039.77],
                    'amil_s80qp'  => ['individual' => 2202.95],
                    'amil_s380qc' => ['coletivo' => 3052.38],
                    'amil_s380qp' => ['individual' => 3265.93],
                    'amil_s450qc' => ['coletivo' => 3360.81],
                    'amil_s450qp' => ['individual' => 3596.16],
                    'amil_s750r1' => ['individual' => 3741.91],
                    'amil_s750r2' => ['individual' => 3779.08]
                ],
                'rio-de-janeiro' => [
                    'amil_s60qc_rj' => ['coletivo' => 951.47],
                    'amil_s380qc'   => ['coletivo' => 1743.81],
                    'amil_s75qc'    => ['coletivo' => 1340.99],
                    'amil_s75qp'    => ['individual' => 1448.27],
                    'amil_s380qp'   => ['individual' => 1935.62],
                    'amil_s450qc'   => ['coletivo' => 1985.83],
                    'amil_s450qp'   => ['individual' => 2204.31],
                    'amil_s580qp'   => ['individual' => 2452.78],
                    'amil_s750r1'   => ['individual' => 2483.64],
                    'amil_s750r2'   => ['individual' => 2508.31]
                ],
                'sao-paulo'      => [
                    'amil_s60qc_jundiai' => ['coletivo' => 1062.19],
                    'amil_s60qc_sp'      => ['coletivo' => 865.87],
                    'amil_s80qc'         => ['coletivo' => 1841.70],
                    'amil_s80qp'         => ['individual' => 1989.03],
                    'amil_s380qc'        => ['coletivo' => 2290.24],
                    'amil_s380qp'        => ['individual' => 2542.24],
                    'amil_s450qc'        => ['coletivo' => 2503.55],
                    'amil_s450qp'        => ['individual' => 2779.01],
                    'amil_s580qp'        => ['individual' => 3097.33],
                    'amil_s750r1'        => ['individual' => 3695.19],
                    'amil_s750r2'        => ['individual' => 3731.88]
                ]
            ];
        } else {
            $valores = 0;
        }

        if (
            !array_key_exists($this->regiao->indice(), $valores)
            || !array_key_exists($this->tipo->indice(), $valores[$this->regiao->indice()])
            || !array_key_exists($this->acomodacao, $valores[$this->regiao->indice()][$this->tipo->indice()])
        ) {
            return 0;
        } elseif (is_numeric($valores)) {
            return $valores;
        }

        return $valores[$this->regiao->indice()][$this->tipo->indice()][$this->acomodacao];
    }
}
