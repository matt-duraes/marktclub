<?php

namespace ORM\Condicao;

trait WhereTrait
{
    /**
     * @param   array       $where          Where em formato array ['campo', '=', 'valor']
     * @param   bool        $obrigatorio    Se será obrigatório passar um where
     * @param   string      $separador      Separador dos campos pondendo ser AND ou OR
     * @param   null|array  $replace        Array para trocar os valores do campo, caso não seja passado, pega a propriedade _replace, passar [] para não validar
     */
    protected function where(array $where, bool $obrigatorio = true, string $separador = 'AND', ?array $replace = null)
    {
        $this->ormCondicao(
            dado: $where,
            obrigatorio: $obrigatorio,
            separador: $separador,
            tipo: 'where',
            replace: $replace
        );
        return $this;
    }

    protected function whereTexto(string $where, array $valor, bool $obrigatorio = true)
    {
        $this->ormCondicaoTexto(
            dado: $where,
            valor: $valor,
            obrigatorio: $obrigatorio,
            tipo: 'where'
        );
        return $this;
    }

    protected function whereOr()
    {
        $where = $this->ormWhereDado;
        if (empty($where)) {
            return $this;
        }
        $this->ormWhereDado[] = ' OR';
        return $this;
    }
}
