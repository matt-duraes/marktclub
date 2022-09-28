<?php

namespace ORM\Salvar;

use Erro\Excecao;
use PDOStatement;

trait UpdateTrait
{
    /**
     * Atualiza um registro
     *
     * @return array Array com os dados que foram atualizados mais o id e uuid quando existir
     */
    protected function update()
    {
        $dado = $this->_dado;
        if (empty($dado)) {
            throw new Excecao(titulo: 'Dado obrigatório!', mensagem: 'Você deve enviar pelo menos um dado para atualizar.');
        }

        $whereDado = $this->ormConverterCondicaoParaString($this->_whereDado);
        $whereValue = $this->_condicaoValue;

        if (empty($whereDado) || !is_string($whereDado) || empty($whereValue) || !is_array($whereValue)) {
            throw new Excecao(titulo: 'Dado obrigatório!', mensagem: 'Você deve passar pelo menos uma condicão para atualizar.');
        }

        $coluna = $this->ormPegarColunaBanco();

        $colunaUuid = '';
        if (array_key_exists('uuid', $coluna)) {
            $colunaUuid = ', `uuid`';
        }

        $existe = $this->readTexto('SELECT `id` ' . $colunaUuid . ' FROM {{TABELA}} WHERE ' . $whereDado . ' LIMIT 0, 1', $whereValue);
        if (!is_array($existe) || !isset($existe[0], $existe[0]->id) || !is_numeric($existe[0]->id)) {
            throw new Excecao(titulo: 'Dado inválido!', mensagem: 'Não foi encontrado nehum dado para atualizar.');
        }

        $colunaDataAtualizacao = $coluna['data_atualizacao'] ?? false;
        $dadoDataAtualizacao = $dado['data_atualizacao'] ?? false;
        if (false !== $colunaDataAtualizacao && false === $dadoDataAtualizacao) {
            $dado['data_atualizacao'] = date('Y-m-d H:i:s');
        }
        $dado = $this->ormValidarDadoParaSalvar($dado, $coluna);

        $campo = [];
        foreach (array_keys($dado) as $ind) {
            $campo[] = '`' . $ind . '` = :' . $ind;
        }

        $query = "UPDATE `{$this->_tabela}` SET " . implode(', ', $campo) . " WHERE {$whereDado}";
        $valorMerge = $dado;
        foreach ($whereValue as $ind => $val) {
            $valorMerge[$ind] = $val;
        }

        $retorno = $this->ormExecute($query, $valorMerge);
        $this->ormResetarOrm();

        if (!$retorno instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro ao atualizar!', mensagem: is_string($retorno) && SISTEMA != 'PRODUCAO' ? $retorno : 'Ocorre um erro ao atualizar, por favor, tente novamente.');
        }

        $idArray = ['id' => $existe[0]->id];
        if (isset($existe[0]->uuid)) {
            $idArray['uuid'] = $existe[0]->uuid;
        }
        $dado = array_merge($idArray, $dado);

        $this->ormSalvarArquivo();
        return $dado;
    }
}
