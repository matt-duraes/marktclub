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
    private function sistemaData(string $mensagem, string $indice, ?string $id = null, ?string $local = null)
    {
        $id = !empty($id) ? $id : $this->id;
        $local = !empty($local) ? $local : $this->ormTabela;
        new Salvar(
            vinculo: $id,
            indice: $indice,
            local: $local,
            mensagem: $mensagem
        );
    }
}
