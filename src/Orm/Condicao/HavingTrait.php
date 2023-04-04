<?php

namespace ORM\Condicao;

trait HavingTrait
{
    /**
     * @param Array     $having         Having em formato array ['campo', '=', 'valor']
     * @param Bool      $obrigatorio    Se será obrigatório passar um having
     * @param String    $separador      Separador dos campos pondendo ser AND ou OR
     */
    protected function having(array $having, bool $obrigatorio = true, string $separador = 'AND')
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
