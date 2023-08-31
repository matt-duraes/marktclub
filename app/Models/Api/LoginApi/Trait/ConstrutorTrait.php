<?php

namespace App\Models\Api\LoginApi\Trait;

use App\Models\Api\ConstrutorClube\ConstrutorEntity;

trait ConstrutorTrait
{
    private function buscarLinkClube()
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['id_admin_empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]]
        ]);
        $this->linkClube = $Construtor->link_clube;
    }
}
