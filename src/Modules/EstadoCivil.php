<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class EstadoCivil implements ModuleInterface
{
    use ValidarTrait;

    private array $listaValores = [1 => 'solteiro', 2 => 'casado', 3 => 'divorciado', 4 => 'viuvo', 5 => 'separado'];
    private array $listaIndiceNome = ['solteiro' => 'Solteiro', 'casado' => 'Casado', 'divorciado' => 'Divorciado', 'viuvo' => 'Viuvo', 'separado' => 'Separado'];

    private string $numero = '';
    public function __toString()
    {
        return $this->estadoCivil();
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->estadoCivil();
    }

    // doc
    /**
     * Gera um modulo de estado civil
     *
     * @param null|int|string   $estadoCivil    Valor do estado civil podendo ser string ou int quando vier do banco
     */
    public function __construct(
        private null|int|string $estadoCivil = null
    ) {
        if (empty($this->estadoCivil())) {
            $this->vazio = true;
            $this->valido = false;
            $this->estadoCivil = '';
            $this->numero = '';
            return;
        } elseif (!$this->validarEstadoCivil()) {
            $this->valido = false;
            $this->estadoCivil = '';
            $this->numero = '';
            return;
        }

        $this->setarValorEstadoCivil();
    }

    private function setarValorEstadoCivil()
    {
        if (in_array($this->estadoCivil, array_keys($this->listaValores))) {
            $this->numero = $this->estadoCivil;
            $this->estadoCivil = $this->listaValores[$this->estadoCivil];
            return;
        } elseif (in_array($this->estadoCivil, $this->listaValores)) {
            $this->numero = array_flip($this->listaValores)[$this->estadoCivil];
            return;
        }

        $this->numero = '';
        $this->estadoCivil = '';
    }

    // doc
    /**
     * Pega um array com a lista de valores válidos no formato indice => nome
     *
     * @param   null|string $titulo     Um titulo para o select
     * @return  array                   Array com os dados
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
     * Pega o valor do índice do estado cívil
     *
     * @return string Retorna o índice do estado civil
     */
    public function estadoCivil(): string
    {
        return is_string($this->estadoCivil) ? $this->estadoCivil : '';
    }

    // doc
    /**
     * Pega o valor do número do estado cívil
     *
     * @return int|string Número do estado cívil
     */
    public function numero(): int|string
    {
        return $this->numero;
    }

    private function validarEstadoCivil(): bool
    {
        $lista = array_merge(array_keys($this->listaValores), array_values($this->listaValores));
        return in_array($this->estadoCivil, $lista);
    }
}
