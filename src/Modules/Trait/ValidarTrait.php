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
     * @return  bool Retorna true para se for vazio ou false para não
     */
    public function vazio(): bool
    {
        return $this->vazio;
    }

    // doc
    /**
     * Validar se o valor é vazio
     *
     * @return  bool Retorna true para se for valido ou false para não
     */
    public function valido(): bool
    {
        return $this->valido;
    }
}
