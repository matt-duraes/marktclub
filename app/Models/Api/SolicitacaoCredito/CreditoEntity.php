<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;
use Erro\Excecao;
use Helpers\ValidarHelper;
use Http\Request;
use Modules\Dinheiro;
use ORM\Entity;

class CreditoEntity extends Entity
{
    private const TIPO_PRAZO_MAXIMO = [
        Tipo::CONSIGNADO       => 96,
        Tipo::CREDITO_PESSOAL  => 24,
        Tipo::VEICULO_NOVO     => 48,
        Tipo::VEICULO_SEMINOVO => 36
    ];
    private const OPERADORA_TIPO_JUROS = [
        Operadora::SICOOB => [
            Tipo::CONSIGNADO       => 1.59,
            Tipo::CREDITO_PESSOAL  => [3.7, 3.1],
            Tipo::VEICULO_NOVO     => 2.1,
            Tipo::VEICULO_SEMINOVO => 3.5
        ]
    ];
    public string $empresa;
    public string $codigo;
    public Operadora $operadora;
    public Tipo $tipo;
    public Dinheiro $valor;
    public string $parcelas;
    public Dinheiro $valor_parcelas;
    public string $observacao;
    public Status $status;
    protected string $ormTabela = TABELA_SOLICITACAO_CREDITO;
    protected array $ormBuscar = [
        'codigo', 'operadora', 'tipo', 'valor', 'empresa',
        'parcelas', 'valor_parcelas', 'observacao', 'status'
    ];
    protected array $ormSalvar = [
        'codigo', 'operadora', 'tipo', 'valor', 'empresa',
        'parcelas', 'valor_parcelas', 'observacao', 'status'
    ];
    private float $juros = 0;

    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->empresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
    }
    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA SIMULAR
    |--------------------------------------------------------------------------
    */
    public function simularCredito()
    {
        $this->validarRequest();
        $this->retornarValorParcelas();
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA SALVAR
    |--------------------------------------------------------------------------
    */

    /**
     * @return void
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $this->codigo = $this->gerarCodigoDaSolicitacao();

        if ((new CreditoModel())->verificarExisteCodigo($this->codigo)) {
            $this->codigo = $this->gerarCodigoDaSolicitacao();
        }
        $this->validarRequest();
        $this->retornarValorParcelas();

    }


    private function validarRequest(): void
    {
        $validarHelper = new ValidarHelper();

        $this->operadora = new Operadora($this->request->operadora ?? '');
        $this->tipo = new Tipo($this->request->tipo ?? '');
        $this->valor = new Dinheiro($this->request->valor ?? '');
        $this->parcelas = $this->request->parcelas ?? '';
        $this->observacao = $this->request->observacao ?? '';
        $this->status = new Status(Status::CRIADA);

        $validarHelper
            ->valor($this->operadora, 'Operadora', 'A Operadora deve ser uma escolha válida.')
            ->obrigatorio()
            ->valido()
            ->valor($this->tipo, 'Tipo', 'O Tipo de solicitação deve ser uma escolha válida.')
            ->obrigatorio()
            ->valido()
            ->valor($this->valor, 'Valor', 'O Valor deve ser um número válido.')
            ->obrigatorio()
            ->valido();

        $prazoMaximo = self::TIPO_PRAZO_MAXIMO[$this->tipo->indice()] ?? 96;

        $validarHelper
            ->valor($this->parcelas, 'Prazo', "O prazo deve ser de 1 à $prazoMaximo.")
            ->obrigatorio()
            ->inteiro()
            ->positivo()
            ->tamanho('>=', 1, 'numero')
            ->tamanho('<=', $prazoMaximo, 'numero');
    }

    private function retornarValorParcelas(): void
    {
        $valorParcelas = match ($this->tipo->indice()) {
            Tipo::CONSIGNADO => $this->jurosConsignado()->calcularParcelas(),
            Tipo::CREDITO_PESSOAL => $this->jurosCreditoPessoal()->calcularParcelas(),
            Tipo::VEICULO_NOVO => $this->jurosVeiculoNovo()->calcularParcelas(),
            Tipo::VEICULO_SEMINOVO => $this->jurosVeiculoSeminovo()->calcularParcelas()
        };

        $this->valor_parcelas = new Dinheiro(number_format($valorParcelas, 2, ',', '.'));
    }

    /**
     * @return string Código aleátorio com base na data e hora atual
     */
    private function gerarCodigoDaSolicitacao(): string
    {
        return uniqid(date('YmdHi') . '-');
    }

    private function calcularParcelas(): float
    {
        return match ($this->operadora->indice()) {
            Operadora::SICOOB => $this->calcularParcelasNaSicoob(),
            default => 0
        };
    }

    private function calcularParcelasNaSicoob(): float
    {
        $juros = $this->juros / 100;
        $valor = (float)$this->valor->decimal();
        $parcelas = (int)$this->parcelas;
        $valorParcelas = ($valor * ((pow((1 + $juros), $parcelas) * $juros) / (pow((1 + $juros), $parcelas) - 1)));

        return match ($this->tipo->indice()) {
            Tipo::CONSIGNADO => $this->calcularSeguroSicoob($valorParcelas),
            default => $valorParcelas
        };
    }

    private function calcularSeguroSicoob(float $valorParcelas): float
    {
        $valor = (float)$this->valor->decimal();
        $parcelas = (int)$this->parcelas;
        $valorSeguro = (($valor * 0.0008) * ($parcelas + 1) / $parcelas);
        return $valorSeguro + $valorParcelas;
    }

    private function jurosConsignado(): self
    {
        $this->juros = self::OPERADORA_TIPO_JUROS[$this->operadora->indice()][Tipo::CONSIGNADO];
        return $this;
    }

    private function jurosCreditoPessoal(): self
    {
        if ($this->operadora->indice() === Operadora::SICOOB) {
            $this->juros = self::OPERADORA_TIPO_JUROS[Operadora::SICOOB][Tipo::CREDITO_PESSOAL][0];
            if ($this->parcelas >= 1 && $this->parcelas <= 12) {
                $this->juros = self::OPERADORA_TIPO_JUROS[Operadora::SICOOB][Tipo::CREDITO_PESSOAL][1];
            }
        }
        return $this;
    }

    private function jurosVeiculoNovo(): self
    {
        $this->juros = self::OPERADORA_TIPO_JUROS[$this->operadora->indice()][Tipo::VEICULO_NOVO];
        return $this;
    }

    private function jurosVeiculoSeminovo(): self
    {
        $this->juros = self::OPERADORA_TIPO_JUROS[$this->operadora->indice()][Tipo::VEICULO_SEMINOVO];
        return $this;
    }
}
