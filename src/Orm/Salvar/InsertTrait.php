<?php

namespace ORM\Salvar;

use Erro\Excecao;
use PDOStatement;
use Modules\Vazio;

trait InsertTrait
{
    /**
     * insere um registro
     *
     * @return array Array com os dados que foram salvos mais o id e uuid quando existir
     */
    protected function insert(): array
    {
        $dado = $this->ormDado;
        if (empty($dado) || $dado instanceof Vazio) {
            throw new Excecao(titulo: 'Campo obrigatório!', mensagem: 'Você precisa passar algum dado para salvar.');
        }

        $coluna = $this->ormPegarColunaBanco();

        if (array_key_exists('uuid', $coluna) && (!array_key_exists('uuid', $dado) || empty($dado['uuid']))) {
            $dado = array_merge(['uuid' => $this->ormUuid()], $dado);
        } elseif (array_key_exists('cod', $coluna) && (!array_key_exists('cod', $dado) || empty($dado['cod']))) {
            $dado = array_merge(['cod' => $this->ormUuid()], $dado);
        }

        $colunaDataCriacao = $coluna['data_criacao'] ?? false;
        $dadoDataCriacao = $dado['data_criacao'] ?? false;
        if (false !== $colunaDataCriacao && false === $dadoDataCriacao) {
            $dado['data_criacao'] = date('Y-m-d H:i:s');
        }
        $colunaDataAtualizacao = $coluna['data_atualizacao'] ?? false;
        $dadoDataAtualizacao = $dado['data_atualizacao'] ?? false;
        if (false !== $colunaDataAtualizacao && false === $dadoDataAtualizacao) {
            $dado['data_atualizacao'] = date('Y-m-d H:i:s');
        }

        foreach ($coluna as $rColuna) {
            if ($rColuna->slug && array_key_exists($rColuna->slug, $dado)) {
                $dado[$rColuna->campo] = $this->ormCriarValorUnico(
                    $rColuna->campo,
                    $this->ormCriarSlug($dado[$rColuna->slug]),
                    $rColuna->tamanho,
                    0
                );
            }
        }
        $dado = $this->ormValidarDadoParaSalvar($dado, $coluna);
        $campoIndice = [];
        foreach (array_keys($dado) as $ind) {
            $campoIndice[] = $ind;
        }

        $campos = '`' . implode('`, `', $campoIndice) . '`';
        $valores = ':' . implode(', :', $campoIndice);
        $query = "INSERT INTO `{$this->ormTabela}` ({$campos}) VALUES ({$valores})";
        $retorno = $this->ormExecute($query, $dado);

        $id = $this->ormUltimoId;

        $this->ormResetarOrm();
        if (!$retorno instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro ao salvar!', mensagem: is_string($retorno) && SISTEMA != 'PRODUCAO' ? $retorno : 'Ocorre um erro ao salvar, por favor, tente novamente.');
        }

        if ($id) {
            $dado = array_merge(['id' => $id], $dado);
        }

        $this->ormSalvarArquivo();
        return $dado;
    }

    private function ormCriarSlug($valor)
    {
        $valor = mb_strtolower(trim($valor), 'UTF-8');
        $valor = preg_replace('/[áàãâä]/ui', 'a', $valor);
        $valor = preg_replace('/[éèêë]/ui', 'e', $valor);
        $valor = preg_replace('/[íìîï]/ui', 'i', $valor);
        $valor = preg_replace('/[óòõôö]/ui', 'o', $valor);
        $valor = preg_replace('/[úùûü]/ui', 'u', $valor);
        $valor = preg_replace('/[ç]/ui', 'c', $valor);
        $valor = trim(preg_replace('/[^a-z0-9]/i', ' ', $valor));
        $valor = preg_replace('/[^a-z0-9]/i', '-', $valor);
        return $valor;
    }
}
