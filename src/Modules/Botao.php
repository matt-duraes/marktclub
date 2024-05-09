<?php

namespace Modules;

use Modules\Trait\SelectTrait;
use Modules\Trait\ValidarTrait;
use Modules\Trait\ValorRealTrait;

final class Botao implements ModuleInterface
{
    use ValidarTrait;
    use SelectTrait;
    use ValorRealTrait;

    public const SIM = 'sim';
    public const NAO = 'nao';

    private array $listaIndiceNome = ['sim' => 'Sim', 'nao' => 'Não'];
    private string|int $numero = '';

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
        return $this->numero;
    }

    // doc
    /**
     * Gera um modulo de botão
     *
     * @param null|int|string $valor Valor podendo ser string sendo sim ou nao ou um int com
     *                               valor 1 para sim ou vazio para nao
     */
    public function __construct(
        private null|int|string $valor = null,
    ) {
        $this->valor_real = $valor;
        if (empty($this->valor)) {
            $this->vazio = true;
            $this->valido = false;
            $this->valor = '';
            $this->numero = '';
            return;
        } elseif (!$this->validarNumero()) {
            $this->valido = false;
            $this->valor = '';
            $this->numero = '';
            return;
        }

        $this->setarValorBotao();
    }

    private function setarValorBotao(): void
    {
        if ($this->valor == 'sim') {
            $this->valor = 'sim';
            $this->numero = 1;
            return;
        } elseif ($this->valor == 'nao') {
            $this->valor = 'nao';
            $this->numero = '';
            return;
        }
    }

    // doc
    /**
     * Pegar o numero do botão
     *
     * @return string|int Retorna 1 para sim e vazio para não
     */
    public function numero(): string|int
    {
        return $this->numero;
    }

    // doc
    /**
     * Pegar o valor do botão
     *
     * @return string Retorna sim ou nao
     */
    public function valor(): string
    {
        return $this->valor == 'sim' ? 'sim' : 'nao';
    }

    // doc
    /**
     * Pegar o valor do botão como boolean
     *
     * @return bool
     */
    public function bool(): string
    {
        return $this->valor == 'sim';
    }

    private function validarNumero(): bool
    {
        return in_array($this->valor, ['sim', 'nao']);
    }
}
