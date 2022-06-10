<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class EnderecoCep implements ModuleInterface
{

    use ValidarTrait;
    public function __toString()
    {
        return $this->cep();
    }

    // doc
    /**
     * Modulo para CEP
     *
     * @param null|string $cep CEP para o modulo
     */
    public function __construct(
        private ?string $cep
    ) {
        if (empty($this->cep)) {
            $this->vazio = true;
            $this->valido = false;
            $this->cep = '';
            return;
        } else if (!$this->validarCep()) {
            $this->valido = false;
            $this->cep = '';
            return;
        }

        $this->cep = preg_replace('/[^0-9]/', '', $this->cep);
    }

    // doc
    /**
     * Recupera o CEP com ponto
     *
     * @return string CEP com ponto
     */
    public function cep(): string
    {
        $cep = $this->cep;
        return empty($cep) ? '' : substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }

    // doc
    /**
     * Pega o CEP com apenas números
     *
     * @return int|string CEP com apenas números
     */
    public function numero(): int|string
    {
        return $this->cep;
    }

    private function validarCep(): bool
    {
        $cep = preg_replace("/[^0-9]/", "", $this->cep);
        return $cep >= 1000000 && $cep <= 99999999;
    }
}
