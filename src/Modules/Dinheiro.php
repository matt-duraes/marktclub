<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Dinheiro implements ModuleInterface
{

    use ValidarTrait;
    public function __toString()
    {
        return $this->dinheiro();
    }

    // doc
    /**
     * Modulo para Dinheiro
     *
     * @param null|string $dinheiro Valor para o modulo
     */
    public function __construct(
        private ?string $dinheiro
    ) {
        if (empty($this->dinheiro)) {
            $this->vazio = true;
            $this->valido = false;
            $this->dinheiro = '';
            return;
        } else if (!$this->validarDinheiro()) {
            $this->valido = false;
            $this->dinheiro = '';
            return;
        }
        $this->setarValor();
    }

    // doc
    /**
     * Pega o valor como dinheiro, por exemplo: 1.000,00
     *
     * @return string Valor em formato de dinheiro
     */
    public function dinheiro(): string
    {
        return number_format($this->dinheiro, 2, ',', '.');
    }

    // doc
    /**
     * Pega o valor em formato decimal
     *
     * @return float Valor em formato float
     */
    public function decimal(): float
    {
        return $this->dinheiro;
    }

    private function validarDinheiro(): bool
    {
        return preg_match('/^[0-9\.\,]{0,}(\.|\,){1}[0-9]{1,2}$/', $this->dinheiro);
    }

    private function setarValor()
    {
        $dinheiro = str_replace(',', '.', $this->dinheiro);
        $explode = explode('.', $dinheiro);

        $centavo = array_pop($explode);
        $valor = implode('', $explode);

        $this->dinheiro = number_format($valor . '.' . $centavo, 2, '.', '');
    }
}
