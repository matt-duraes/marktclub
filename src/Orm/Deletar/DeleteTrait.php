<?php

namespace ORM\Deletar;

use Erro\Erro;
use Erro\Excecao;
use PDOStatement;

trait DeleteTrait
{
    /**
     * Método que manda o ORM deletar o registro
     *
     * @return bool             Retorna true em caso de sucesso
     * @throws \Erro\Excecao    Retorna uma Exceção em caso de erro
     */
    protected function delete(): bool
    {
        $where = $this->ormConverterCondicaoParaString($this->_whereDado);
        $dado = $this->_condicaoValue;

        if (empty($where) || empty($dado)) {
            throw new Erro(mensagem: 'Não foi passado nenhuma condição para deletar.');
        }

        $query = "DELETE FROM `{$this->_tabela}` WHERE " . $where;
        $retorno = $this->ormExecute($query, $dado);

        $this->ormResetarOrm();
        if (!$retorno instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro ao deletar!', mensagem: is_string($retorno) && SISTEMA != 'PRODUCAO' ? $retorno : 'Ocorre um erro ao deletar, por favor, tente novamente.');
        }
        return true;
    }
}
