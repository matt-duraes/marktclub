<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Inteiro implements ModuleInterface
{
    use ValidarTrait;

    private string $valor = '';

    public function __toString()
    {
        return $this->numero();
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
     * Gera um modulo de gênero
     *
     * @param null|int|string $genero Valor do Genero podendo ser string ou int quando vier do banco
     */
    public function __construct(
        private null|int|string $numero = null,
    ) {
        if (empty($numero)) {
            $this->vazio = true;
            $this->valido = false;
            $this->numero = '';
            return;
        } elseif (!is_int($numero)) {
            $this->valido = false;
            $this->numero = '';
            return;
        }

        $this->valor = $numero;
    }

    // doc
    /**
     * Pega o número do gênero
     *
     * @return int|string Número do gênero
     */
    public function numero(): int|string
    {
        return $this->numero;
    }
}
