<?php

namespace ORM\Join;

use Erro\Excecao;

trait JoinTrait
{
    /**
     * Faz um inner join com outra tabela
     *
     * @param string     $campo    Campo da tabela atual
     * @param string     $relacao  Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param string     $condicao Condição para o JOIN
     * @param string     $tabela   Tabela caso não queira usar a tabela original
     * @param null|array $replace  Array para trocar os valores do campo, caso não seja passado, pega a
     *                             propriedade _replace, passar [] para não validar
     */
    protected function innerJoin(
        string $campo,
        string $relacao = '',
        string $condicao = '=',
        string $tabela = '',
        ?array $replace = null
    ): self {
        $this->join($campo, $relacao, $condicao, $tabela, 'INNER', $replace);
        return $this;
    }

    /**
     * Faz um left join com outra tabela
     *
     * @param string     $campo    Campo da tabela atual
     * @param string     $relacao  Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param string     $condicao Condição para o JOIN
     * @param string     $tabela   Tabela caso não queira usar a tabela original
     * @param null|array $replace  Array para trocar os valores do campo, caso não seja passado,
     *                             pega a propriedade _replace, passar [] para não validar
     */
    protected function leftJoin(
        string $campo,
        string $relacao = '',
        string $condicao = '=',
        string $tabela = '',
        ?array $replace = null
    ): self {
        $this->join($campo, $relacao, $condicao, $tabela, 'LEFT', $replace);
        return $this;
    }

    /**
     * Faz um right join com outra tabela
     *
     * @param string     $campo    Campo da tabela atual
     * @param string     $relacao  Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param string     $condicao Condição para o JOIN
     * @param string     $tabela   Tabela caso não queira usar a tabela original
     * @param null|array $replace  Array para trocar os valores do campo, caso não seja passado, pega
     *                             a propriedade _replace, passar [] para não validar
     */
    protected function rightJoin(
        string $campo,
        string $relacao = '',
        string $condicao = '=',
        string $tabela = '',
        ?array $replace = null
    ) {
        $this->join($campo, $relacao, $condicao, $tabela, 'RIGHT', $replace);
        return $this;
    }

    /**
     * Faz um cross join com outra tabela
     *
     * @param string     $campo    Campo da tabela atual
     * @param string     $relacao  Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param string     $condicao Condição para o JOIN
     * @param string     $tabela   Tabela caso não queira usar a tabela original
     * @param null|array $replace  Array para trocar os valores do campo, caso não seja passado,
     *                             pega a propriedade _replace, passar [] para não validar
     */
    protected function crossJoin(
        string $campo,
        string $relacao = '',
        string $condicao = '=',
        string $tabela = '',
        ?array $replace = null
    ) {
        $this->join($campo, $relacao, $condicao, $tabela, 'CROSS', $replace);
        return $this;
    }

    /**
     * Faz um full join com outra tabela
     *
     * @param string     $campo    Campo da tabela atual
     * @param string     $relacao  Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param string     $condicao Condição para o JOIN
     * @param string     $tabela   Tabela caso não queira usar a tabela original
     * @param null|array $replace  Array para trocar os valores do campo, caso não seja passado,
     *                             pega a propriedade _replace, passar [] para não validar
     */
    protected function fullJoin(
        string $campo,
        string $relacao = '',
        string $condicao = '=',
        string $tabela = '',
        ?array $replace = null
    ): self {
        $this->join($campo, $relacao, $condicao, $tabela, 'FULL', $replace);
        return $this;
    }

    /**
     * Faz um join com outra tabela
     *
     * @param string     $campo    Campo da tabela atual
     * @param string     $relacao  Campo da tabela original ou se tiver passado o parâmetro $tabela
     * @param string     $condicao Condição para o JOIN
     * @param string     $tabela   Tabela caso não queira usar a tabela original
     * @param string     $tipo     Qual tipo de JOIN será usado podendo ser: INNER, LEFT, RIGHT, CROSS ou FULL
     * @param null|array $replace  Array para trocar os valores do campo, caso não seja
     *                             passado, pega a propriedade _replace, passar [] para não validar
     */
    protected function join(
        string $campo,
        string $relacao = '',
        string $condicao = '=',
        string $tabela = '',
        string $tipo = 'INNER',
        ?array $replace = null
    ): self {
        $tabela = !empty($tabela) ? $tabela : $this->ormTabela;
        if (!in_array($tipo, ['INNER', 'LEFT', 'RIGHT', 'CROSS', 'FULL'])) {
            throw new Excecao(
                titulo: 'Campo incorreto!',
                mensagem: 'O tipo de JOIN (' . $tipo . ') não é um valor padrão.'
            );
        } elseif (!in_array($condicao, $this->ormCondicao)) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Valor de condição incorreto (' . $condicao . ').');
        } elseif ($this->ormTabela == $this->ormTabelaAtual) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Você deve mudar a tabela para o Join.');
        }

        $replaceCampo = array_flip($this->pegarReplace($this->ormTabelaAtual));
        $replaceRelacao = array_flip($this->pegarReplace($tabela));
        $campo = array_key_exists($campo, $replaceCampo) ? $replaceCampo[$campo] : $campo;
        $relacao = array_key_exists($relacao, $replaceRelacao) ? $replaceRelacao[$relacao] : $relacao;

        $this->ormJoin[] = $tipo . " JOIN `{$this->ormTabelaAtual}` ON `{$this->ormTabelaAtual}`.`{$campo}` {$condicao} `{$tabela}`.`{$relacao}`";
        return $this;
    }

    /**
     * Faz um Join usando texto puro, cuidado ao usá-lo
     *
     * @param string $join Join em texto puro
     */
    protected function joinTexto(string $join = ''): self
    {
        $this->ormJoin[] = $join;
        return $this;
    }
}
