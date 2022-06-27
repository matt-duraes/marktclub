<?php

namespace Painel\Agenda\Models\Trait;

use Helpers\CryptHelper;

trait Id
{
    /**
     * Remove a criptografia do ID
     *
     * @param string $id ID que deve ser descriptografado
     * @return string
     */
    public function pegarId(string $id)
    {
        return (new CryptHelper())->decode($id);
    }

    /**
     * Adiciona criptografia ao ID
     *
     * @param string $id ID que deve ser criptografado
     * @return string
     */
    public function setarId(string $id)
    {
        return (new CryptHelper())->encode($id);
    }
}
