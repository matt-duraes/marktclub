<?php

namespace Modules\Trait;

trait ValidarTrait
{
    private bool $vazio = false;
    private bool $valido = true;

    // doc
    /**
     * Validar se o valor é vazio
     *
     * @return bool Retorna true para se for vazio ou false para não
     */
    public function vazio(): bool
    {
        return $this->vazio;
    }

    // doc
    /**
     * Validar se o valor é vazio
     *
     * @return bool Retorna true para se for valido ou false para não
     */
    public function valido(): bool
    {
        return $this->valido;
    }

    // doc
    /**
     * Valida se o modulo é vazio e válido
     *
     * @param string  $campo O nome do campo que deve retornar no erro
     * @param boolean $vazio Se vai validar se o o campo é vazio
     */
    public function validar(string $campo, bool $vazio = true): void
    {
        if ($vazio && $this->vazio) {
            mensagemErro('Campo obrigatório!', 'O campo ' . $campo . ' é obrigatório.');
        } elseif (!$this->vazio && !$this->valido) {
            mensagemErro('Campo inválido!', 'O campo ' . $campo . ' não é válido.');
        }
    }
}
