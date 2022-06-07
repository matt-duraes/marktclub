<?php

namespace ORM\Join;

use Erro\Excecao;

trait JoinTrait
{
    /**
     * @param String        $campo      Campo da tabela atual
     * @param String        $relacao    Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param String        $condicao   Condição para o JOIN
     * @param String        $tabela     Tabela caso não queira usar a tabela original
     */
    protected function innerJoin(string $campo, string $relacao = '', string $condicao = '=', string $tabela = '')
    {
        $this->join($campo, $relacao, $condicao, $tabela, 'INNER');
        return $this;
    }

    /**
     * @param String        $campo      Campo da tabela atual
     * @param String        $relacao    Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param String        $condicao   Condição para o JOIN
     * @param String        $tabela     Tabela caso não queira usar a tabela original
     */
    protected function leftJoin(string $campo, string $relacao = '', string $condicao = '=', string $tabela = '')
    {
        $this->join($campo, $relacao, $condicao, $tabela, 'LEFT');
        return $this;
    }

    /**
     * @param String        $campo      Campo da tabela atual
     * @param String        $relacao    Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param String        $condicao   Condição para o JOIN
     * @param String        $tabela     Tabela caso não queira usar a tabela original
     */
    protected function rightJoin(string $campo, string $relacao = '', string $condicao = '=', string $tabela = '')
    {
        $this->join($campo, $relacao, $condicao, $tabela, 'RIGHT');
        return $this;
    }

    /**
     * @param String        $campo      Campo da tabela atual
     * @param String        $relacao    Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param String        $condicao   Condição para o JOIN
     * @param String        $tabela     Tabela caso não queira usar a tabela original
     */
    protected function crossJoin(string $campo, string $relacao = '', string $condicao = '=', string $tabela = '')
    {
        $this->join($campo, $relacao, $condicao, $tabela, 'CROSS');
        return $this;
    }

    /**
     * @param String        $campo      Campo da tabela atual
     * @param String        $relacao    Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param String        $condicao   Condição para o JOIN
     * @param String        $tabela     Tabela caso não queira usar a tabela original
     */
    protected function fullJoin(string $campo, string $relacao = '', string $condicao = '=', string $tabela = '')
    {
        $this->join($campo, $relacao, $condicao, $tabela, 'FULL');
        return $this;
    }

    /**
     * Faz um join com outra tabela
     *
     * @param   string      $campo      Campo da tabela atual
     * @param   string      $relacao    Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param   string      $condicao   Condição para o JOIN
     * @param   string      $tabela     Tabela caso não queira usar a tabela original
     * @param   string      $tipo       Qual tipo de JOIN será usado podendo ser: INNER, LEFT, RIGHT, CROSS ou FULL
     * @return  self
     */
    protected function join(string $campo, string $relacao = '', string $condicao = '=', string $tabela = '', string $tipo = 'INNER'): self
    {
        $tabela = !empty($tabela) ? $tabela : $this->_tabela;
        if (!in_array($tipo, ['INNER', 'LEFT', 'RIGHT', 'CROSS', 'FULL'])) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'O tipo de JOIN (' . $tipo . ') não é um valor padrão.');
        } elseif (!in_array($condicao, $this->_condicao)) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Valor de condição incorreto (' . $condicao . ').');
        } elseif ($this->_tabela == $this->_tabelaAtual) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Você deve mudar a tabela para o Join.');
        }
        $this->_join[] = $tipo . " JOIN `{$this->_tabelaAtual}` ON `{$this->_tabelaAtual}`.`{$campo}` {$condicao} `{$tabela}`.`{$relacao}`";
        return $this;
    }

    /**
     * Faz um Join usando texto puro, cuidado ao usá-lo
     *
     * @param   string        $join       Join em texto puro
     * @return  self
     */
    protected function joinTexto(string $join = ''): self
    {
        $this->_join[] = $join;
        return $this;
    }
}
