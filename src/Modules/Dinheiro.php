<?php

namespace Modules;

use Modules\Trait\ValidarTrait;
use Modules\Trait\ValorRealTrait;

final class Dinheiro implements ModuleInterface
{
    use ValidarTrait;
    use ValorRealTrait;

    /**
     * Modulo para Dinheiro
     *
     * @param string|null $dinheiro Valor para o modulo
     */
    public function __construct(
        private ?string $dinheiro = null
    ) {
        $this->valor_real = $dinheiro;
        if (empty($this->dinheiro)) {
            $this->vazio = true;
            $this->valido = false;
            $this->dinheiro = '';
            return;
        } elseif (!$this->validarDinheiro()) {
            $this->valido = false;
            $this->dinheiro = '';
            return;
        }
        $this->setarValor();
    }

    // doc

    /**
     * @return bool
     */
    private function validarDinheiro(): bool
    {
        return preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $this->dinheiro) === 1;
    }

    private function setarValor(): void
    {
        $this->dinheiro = number_format($this->dinheiro, 2, '.', '');
    }

    // doc

    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return string|float
     */
    public function banco(): string|float
    {
        return $this->decimal();
    }

    // doc

    /**
     * Pega o valor em formato decimal
     *
     * @return float|string Valor em formato float
     */
    public function decimal(): float|string
    {
        return $this->dinheiro;
    }

    // doc

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dinheiro();
    }

    /**
     * Pega o valor como dinheiro, por exemplo: 1.000,00
     *
     * @return string Valor em formato de dinheiro
     */
    public function dinheiro(): string
    {
        return empty($this->dinheiro) ? '' : number_format($this->dinheiro, 2, ',', '.');
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     *
     * @return string
     */
    public function valor(): string
    {
        return $this->decimal();
    }
}
