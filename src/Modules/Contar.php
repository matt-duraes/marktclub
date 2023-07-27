<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Contar implements ModuleInterface
{
    use ValidarTrait;

    private int $numero = 0;

    public function __toString()
    {
        return $this->valor();
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->numero();
    }

    // doc
    /**
     * Gera um modulo de botão
     *
     * @param null|int|string $valor Valor podendo ser string sendo sim ou nao ou um int com
     *                               valor 1 para sim ou vazio para nao
     */
    public function __construct(
        private null|int|string $valor,
    ) {
        if (!is_numeric($valor) && empty($valor)) {
            $this->valido = false;
            $this->vazio = true;
            return;
        } elseif (!is_numeric($valor)) {
            $this->valido = false;
            $this->vazio = false;
            return;
        } elseif (is_numeric($valor)) {
            return;
        }

        $this->numero = $valor;
    }

    // doc
    /**
     * Adicionar um número ao valor atual
     *
     * @param int $numero Número a ser adicionado
    */
    public function adicionar(int $numero): void
    {
        $this->numero += $numero;
    }

    // doc
    /**
     * Remove um número ao valor atual
     *
     * @param int $numero Número a ser removido
     */
    public function remover(int $numero): void
    {
        $this->numero -= $numero;
    }

    // doc
    /**
     * Pega o numero atual
     *
     * @return int
     */
    public function numero(): int
    {
        return (int)$this->numero;
    }

    // doc
    /**
     * Pegar o numero atual
     *
     * @return string Retorna sim ou nao
     */
    public function valor(): int
    {
        return $this->numero;
    }
}
