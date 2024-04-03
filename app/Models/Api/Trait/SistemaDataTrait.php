<?php

namespace App\Models\Api\Trait;

use ApiModel\Data\Salvar;

trait SistemaDataTrait
{
    private function sistemaData(string $mensagem)
    {
        new Salvar(
            vinculo: $this->id,
            local: $this->ormTabela,
            mensagem: $mensagem
        );
    }
}
