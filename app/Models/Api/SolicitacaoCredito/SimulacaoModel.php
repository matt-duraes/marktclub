<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Tipo;
use Erro\Excecao;
use Modules\Dinheiro;
use Modules\Inteiro;

final class SimulacaoModel
{
    use ValidarTrait;

    private const OPERADORA_TIPO_JUROS = [
        Operadora::SICOOB => [
            Tipo::CONSIGNADO       => 1.59,
            Tipo::CREDITO_PESSOAL  => [3.7, 3.1],
            Tipo::VEICULO_NOVO     => 2.1,
            Tipo::VEICULO_SEMINOVO => 3.5
        ]
    ];
    public Dinheiro $valorParcela;
    private float $juros;

    /**
     * @param Operadora $operadora
     * @param Tipo      $tipo
     * @param Dinheiro  $valor_total
     * @param Inteiro   $parcela
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Operadora $operadora,
        private readonly Tipo $tipo,
        private readonly Dinheiro $valor_total,
        private readonly Inteiro $parcela
    ) {
        $this
            ->validarOperadora()
            ->validarTipo()
            ->validarValor()
            ->validarParcela();

        $this->setarValorParcela();
    }

    private function setarValorParcela(): void
    {
        $valorParcela = match ($this->tipo->indice()) {
            Tipo::CONSIGNADO => $this->jurosConsignado()->calcularParcela(),
            Tipo::CREDITO_PESSOAL => $this->jurosCreditoPessoal()->calcularParcela(),
            Tipo::VEICULO_NOVO => $this->jurosVeiculoNovo()->calcularParcela(),
            Tipo::VEICULO_SEMINOVO => $this->jurosVeiculoSeminovo()->calcularParcela()
        };

        $this->valorParcela = new Dinheiro((string)$valorParcela);
    }

    /**
     * @return float
     */
    private function calcularParcela(): float
    {
        return match ($this->operadora->indice()) {
            Operadora::SICOOB => $this->calcularParcelaNaSicoob(),
            default => 0
        };
    }

    /**
     * @return float
     */
    private function calcularParcelaNaSicoob(): float
    {
        $juros = $this->juros / 100;
        $valor = (float)$this->valor_total->decimal();
        $parcela = (int)$this->parcela->numero();
        $valorParcela = ($valor * ((pow((1 + $juros), $parcela) * $juros) / (pow((1 + $juros), $parcela) - 1)));

        return match ($this->tipo->indice()) {
            Tipo::CONSIGNADO => $this->calcularSeguroSicoob($valorParcela),
            default => $valorParcela
        };
    }

    /**
     * @param float $valorParcela
     *
     * @return float
     */
    private function calcularSeguroSicoob(float $valorParcela): float
    {
        $valor = (float)$this->valor_total->decimal();
        $parcela = (int)$this->parcela->numero();
        $valorSeguro = (($valor * 0.0008) * ($parcela + 1) / $parcela);
        return $valorParcela + $valorSeguro;
    }

    /**
     * @return self
     */
    private function jurosConsignado(): self
    {
        $this->juros = self::OPERADORA_TIPO_JUROS[$this->operadora->indice()][Tipo::CONSIGNADO];
        return $this;
    }

    /**
     * @return self
     */
    private function jurosCreditoPessoal(): self
    {
        $parcela = $this->parcela->numero();
        if ($this->operadora->indice() === Operadora::SICOOB) {
            $this->juros = self::OPERADORA_TIPO_JUROS[Operadora::SICOOB][Tipo::CREDITO_PESSOAL][0];
            if ($parcela >= 1 && $parcela <= 12) {
                $this->juros = self::OPERADORA_TIPO_JUROS[Operadora::SICOOB][Tipo::CREDITO_PESSOAL][1];
            }
        }
        return $this;
    }

    /**
     * @return self
     */
    private function jurosVeiculoNovo(): self
    {
        $this->juros = self::OPERADORA_TIPO_JUROS[$this->operadora->indice()][Tipo::VEICULO_NOVO];
        return $this;
    }

    /**
     * @return self
     */
    private function jurosVeiculoSeminovo(): self
    {
        $this->juros = self::OPERADORA_TIPO_JUROS[$this->operadora->indice()][Tipo::VEICULO_SEMINOVO];
        return $this;
    }
}
