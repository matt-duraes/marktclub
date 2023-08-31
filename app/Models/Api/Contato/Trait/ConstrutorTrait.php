<?php

namespace App\Models\Api\Contato\Trait;

use App\Models\Api\ConstrutorClube\ConstrutorEntity;

trait ConstrutorTrait
{
    private function buscarIdEmpresa()
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_site', $this->url],
            ['status', 1]
        ]);

        $this->idEmpresa = $Construtor->id_admin_empresa;
    }
}
