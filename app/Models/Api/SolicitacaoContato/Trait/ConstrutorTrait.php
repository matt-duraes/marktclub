<?php

namespace App\Models\Api\SolicitacaoContato\Trait;

use App\Classes\Geral\Status;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;

trait ConstrutorTrait
{
    private function buscarIdEmpresa(): void
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_clube', $this->url],
            ['status', (new Status(Status::ATIVO))->numero()]
        ], false);
        $this->idEmpresa = empty($Construtor->id) ?: $Construtor->id_admin_empresa;
    }
}
