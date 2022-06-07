<?php

namespace ORM\Order;

use Order\OrderInterface;

trait OrderTrait
{
    /**
     * Ordena a busca no banco
     *
     * @param   string|array|OrderInterface     $campo      Campo para a busca podendo ser uma string, um array nos formatos ["campo_1", "campo_2"] ou [["campo_1", "ASC"], ["campo_2", "DESC"]] ou um OrderInterface
     * @param   string                          $direcao    Direção podendo ser ASC ou DESC, ASC por padrão
     * @return  self
     */
    protected function order(string|array|OrderInterface $campo, string $direcao = 'ASC'): self
    {
        if ($campo instanceof OrderInterface) {
            $this->_order = [$campo->ordem()];
            return $this;
        }
        $verificar = is_string($campo) ? mb_strtolower($campo, 'UTF-8') : '';

        $order = [];
        if (in_array($verificar, ['rand', 'rand()'])) {
            $order[] = 'RAND()';
        } elseif (is_string($campo)) {
            $order[] = '`' . $this->_tabelaAtual . '`.`' . $campo . '` ' . $direcao;
        } elseif (is_array($campo) && is_string($campo[0])) {
            foreach ($campo as $r) {
                $order[] = '`' . $this->_tabelaAtual . '`.`' . $r . '` ' . $direcao;
            }
        } elseif (is_array($campo) && is_array($campo[0])) {
            foreach ($campo as $r) {
                $direcaoTemporaria = $r[1] ?? $direcao;
                $order[] = '`' . $this->_tabelaAtual . '`.`' . $r[0] . '` ' . $direcaoTemporaria;
            }
        }
        $this->_order = $order;
        return $this;
    }

    /**
     * Ordem em texto puro
     *
     * @param   string $order   Ordem que deseja buscar
     * @return  self
     */
    protected function orderTexto(string $order): self
    {
        $this->_order = [$order];
        return $this;
    }

    private function ormMontarOrderFinal()
    {
        return implode(', ', $this->_order);
    }
}
