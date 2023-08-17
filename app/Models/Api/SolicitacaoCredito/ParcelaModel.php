<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Tipo;

final class ParcelaModel
{
    use ValidarTrait;

    public const TIPO_PRAZO_MAXIMO = [
        Tipo::CONSIGNADO       => 96,
        Tipo::CREDITO_PESSOAL  => 24,
        Tipo::VEICULO_NOVO     => 48,
        Tipo::VEICULO_SEMINOVO => 36
    ];

    public array $listaParcela;

    public function __construct(
        private readonly Operadora $operadora,
        private readonly Tipo $tipo,
        string $titulo = null
    ) {
        $this
            ->validarOperadora()
            ->validarTipo();

        if (!empty($titulo)) {
            $this->listaParcela[''] = $titulo;
        }

        match ($this->tipo->indice()) {
            Tipo::CONSIGNADO => $this->setarListaFixa('1,59'),
            Tipo::CREDITO_PESSOAL => $this->setarListaCreditoPessoal(),
            Tipo::VEICULO_NOVO => $this->setarListaFixa('2,10'),
            Tipo::VEICULO_SEMINOVO => $this->setarListaFixa('3,50')
        };
    }

    /**
     * @param string $juros
     */
    private function setarListaFixa(string $juros): void
    {
        $i = 2;
        $this->listaParcela[1] = '1 mês - ' . $juros . '% a.m.';
        for (; $i <= self::TIPO_PRAZO_MAXIMO[Tipo::CONSIGNADO]; ++$i) {
            $this->listaParcela[$i] = $i . ' meses - ' . $juros . ' a.m.';
        }
    }

    private function setarListaCreditoPessoal(): void
    {
        $i = 2;
        $this->listaParcela[1] = '1 mês - 1,59% a.m.';
        for (; $i <= self::TIPO_PRAZO_MAXIMO[Tipo::CONSIGNADO]; ++$i) {
            $juros = $i <= 12 ? '3,10%' : '3,70';
            $this->listaParcela[$i] = $i . ' meses - ' . $juros . ' a.m.';
        }
    }
}
