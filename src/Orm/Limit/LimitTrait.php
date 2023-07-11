<?php

namespace ORM\Limit;

trait LimitTrait
{
    /**
     * Limit para a busca
     *
     * @param  int  $inicio     Valor inicial para o LIMIT
     * @param  int  $quantidade Quando de registros a serem buscados
     * @return self
     */
    protected function limit(int $inicio, int $quantidade): self
    {
        $this->ormLimit = $inicio . ', ' . $quantidade;
        return $this;
    }

    /**
     * Pagina atual que deseja buscar
     *
     * @param  int  $pagina     Página atual da busca
     * @param  int  $quantidade Quantidade de registro por página
     * @return self
     */
    protected function pagina(int $pagina, int $quantidade = 20): self
    {
        $this->ormLimitPagina = $pagina;
        $this->ormLimitQuantidade = $quantidade;
        $this->ormLimit = ($pagina - 1) * $quantidade . ', ' . $quantidade;
        $this->ormPaginacao = true;
        return $this;
    }

    protected function paginacao($lista = [])
    {
        if ($this->paginacaoLista) {
            $paginacao = $this->paginacaoLista;
            $paginacao->lista = $lista;

            return $paginacao;
        } else {
            return [];
        }
    }
}
