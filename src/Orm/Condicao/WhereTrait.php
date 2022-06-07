<?php

namespace ORM\Condicao;

trait WhereTrait
{
    /**
     * @param Array     $where          Where em formato array ['campo', '=', 'valor']
     * @param Bool      $obrigatorio    Se será obrigatório passar um where
     * @param String    $separador      Separador dos campos pondendo ser AND ou OR
     */
    protected function where(array $where, bool $obrigatorio = true, string $separador = 'AND')
    {
        $this->ormCondicao($where, $obrigatorio, $separador, 'where');
        return $this;
    }

    protected function whereTexto(string $where, array $valor, bool $obrigatorio = true)
    {
        $this->ormCondicaoTexto($where, $valor, $obrigatorio, 'where');
        return $this;
    }

    protected function whereOr()
    {
        $where = $this->_whereDado;
        if (empty($where)) {
            return $this;
        }
        $this->_whereDado[] = ' OR';
        return $this;
    }
}
