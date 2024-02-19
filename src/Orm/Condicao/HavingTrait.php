<?php

namespace ORM\Condicao;

use Where\WhereInterface;

trait HavingTrait
{
    /**
     * @param array  $having      Having em formato array ['campo', '=', 'valor']
     * @param bool   $obrigatorio Se será obrigatório passar um having
     * @param string $separador   Separador dos campos pondendo ser AND ou OR
     */
    protected function having(array|WhereInterface $having, bool $obrigatorio = true, string $separador = 'AND')
    {
        $this->ormCondicao($having, $obrigatorio, $separador, 'having');
        return $this;
    }

    protected function havingTexto(string $having, array $valor, bool $obrigatorio = true)
    {
        $this->ormCondicaoTexto($having, $valor, $obrigatorio, 'having');
        return $this;
    }

    protected function havingOr()
    {
        $having = $this->ormHavingDado;
        if (empty($having)) {
            return $this;
        }
        $this->ormHavingDado[] = ' OR';
        return $this;
    }
}
