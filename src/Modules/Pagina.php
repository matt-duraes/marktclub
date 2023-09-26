<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Pagina implements ModuleInterface
{
    use ValidarTrait;

    private string $valor = '';

    public function __toString()
    {
        return $this->numero();
    }

    public function padrao()
    {
        return 1;
    }

    /**
     * Pega o número passado ou o padrão casa o número seja vazio ou inválido
     *
     * @return int
     */
    public function numeroPadrao(): int
    {
        return $this->valido() ? $this->numero() : $this->padrao();
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
        } elseif (!preg_match('/^[0-9\-]{1,}$/', $numero)) {
            $this->valido = false;
            $this->numero = '';
            return;
        }
        $this->valor = (int)$numero;
    }

    // doc
    /**
     * Pega o número do gênero
     *
     * @return int|string Número do gênero
     */
    public function numero(): int|string
    {
        return !empty($this->numero) ? (int)$this->numero : '';
    }
}
