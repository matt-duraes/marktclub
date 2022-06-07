<?php

namespace Random;

trait Outros
{
    public function simNao()
    {
        return ['sim', 'nao'][rand(0, 1)];
    }

    /**
     * Pegar um valor aleatório do array passado
     *
     * @param array $dado Array com os campos desejados
     */
    public function random(array $dado)
    {
        return $dado[rand(0, count($dado) - 1)];
    }
}
