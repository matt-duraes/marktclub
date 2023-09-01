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

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->numero();
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->numero();
    }

    // doc
    /**
     * Modulo para CEP
     *
     * @param null|string $cep CEP para o modulo
     */
    public function __construct(
        private ?string $cep = null
    ) {
        if (empty($this->cep)) {
            $this->vazio = true;
            $this->valido = false;
            $this->cep = '';
            return;
        } elseif (!$this->validarCep()) {
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
        $cep = str_pad($this->cep, 8, '0', STR_PAD_LEFT);
        return empty($cep) ? '' : substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }

    // doc
    /**
     * Pega o CEP com apenas números
     *
     * @return string|int CEP com apenas números ou string vazio
     */
    public function numero(): string|int
    {
        return !empty($this->cep) ? (int)$this->cep : '';
    }

    private function validarCep(): bool
    {
        $cep = preg_replace('/[^0-9]/', '', $this->cep);
        return preg_match('/^[0-9]{8}$/', $cep);
    }
}
