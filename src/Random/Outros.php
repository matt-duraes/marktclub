<?php

namespace Random;

trait Outros
{
    /**
     * @return string
     */
    public function simNao(): string
    {
        return ['sim', 'nao'][rand(0, 1)];
    }

    /**
     * Pegar um valor aleatório do array passado
     *
     * @param  array $dado Array com os campos desejados
     * @return mixed
     */
    public function random(array $dado): mixed
    {
        return $dado[rand(0, count($dado) - 1)];
    }
}
