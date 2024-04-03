<?php

namespace App\Models\Api\Trait;

use ApiModel\Data\Salvar;

trait SistemaDataTrait
{
    /**
     * Adicionar um registro no sistema de data
     *
     * @param string $mensagem Mensagem que retorna para o usuário
     * @param string $indice   Índice para fazer filtros
     */
    private function sistemaData(string $mensagem, string $indice)
    {
        new Salvar(
            vinculo: $this->id,
            indice: $indice,
            local: $this->ormTabela,
            mensagem: $mensagem
        );
    }
}
