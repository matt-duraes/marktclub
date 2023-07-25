<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Quantidade implements ModuleInterface
{
    use ValidarTrait;

    private string $valor = '';

    public function __toString()
    {
        return $this->numero();
    }

    public function padrao()
    {
        return $this->vazio() ? 20 : $this->numero();
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
            $this->numero = 20;
            return;
        } elseif (!preg_match('/^[1-9]{1}[0-9]{0,}$/', $numero)) {
            $this->valido = false;
            $this->numero = 20;
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
