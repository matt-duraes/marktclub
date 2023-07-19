<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Genero implements ModuleInterface
{
    use ValidarTrait;

    private array $listaValores = [1 => 'masculino', 2 => 'feminino', 3 => 'outro', 4 => 'nao-informar'];
    private array $listaIndiceNome = ['masculino' => 'Masculino', 'feminino' => 'Feminino', 'outro' => 'Outro', 'nao-informar' => 'Não informado'];
    private string $valor = '';
    private string|int $numero;

    public function __toString()
    {
        return $this->genero();
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->genero();
    }

    // doc
    /**
     * Gera um modulo de gênero
     *
     * @param null|int|string $genero Valor do Genero podendo ser string ou int quando vier do banco
     */
    public function __construct(
        private null|int|string $genero = null,
    ) {
        if (empty($this->genero)) {
            $this->vazio = true;
            $this->valido = false;
            $this->numero = '';
            $this->genero = '';
            return;
        } elseif (!$this->validarGenero()) {
            $this->valido = false;
            $this->numero = '';
            $this->genero = '';
            return;
        }

        $this->setarValorGenero();
    }

    private function setarValorGenero(): void
    {
        if (in_array($this->genero, array_keys($this->listaValores))) {
            $this->numero = $this->genero;
            $this->genero = $this->listaValores[$this->genero];
            return;
        } elseif (in_array($this->genero, $this->listaValores)) {
            $this->numero = array_flip($this->listaValores)[$this->genero];
            return;
        }

        $this->numero = '';
        $this->genero = '';
    }

    // doc
    /**
     * Pega um array com a lista de valores válidos no formato indice => nome
     *
     * @param  null|string $titulo Um titulo para o select
     * @return array       Array com os dados
     */
    public function select(?string $titulo = null): array
    {
        if (!empty($titulo)) {
            return ['' => $titulo] + $this->listaIndiceNome;
        }
        return $this->listaIndiceNome;
    }

    // doc
    /**
     * Pega o valor do índice do gênero
     *
     * @return string Valor do índice do gênero
     */
    public function genero(): string
    {
        return $this->genero;
    }

    // doc
    /**
     * Pega o número do gênero
     *
     * @return int|string Número do gênero
     */
    public function numero(): int|string
    {
        return $this->numero;
    }

    private function validarGenero(): bool
    {
        $lista = array_merge(array_keys($this->listaValores), array_values($this->listaValores));
        return in_array($this->genero, $lista);
    }
}
