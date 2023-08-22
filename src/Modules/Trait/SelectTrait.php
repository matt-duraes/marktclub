<?php

namespace Modules\Trait;

trait SelectTrait
{
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
}
