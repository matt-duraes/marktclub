<?php

namespace App\Models\Api\Demanda\Relatorio;

use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class GerarTodosModel {
    public function __construct(int $id, array $demanda)
    {
        $Demanda = new RelatorioSprintModel($demanda);

        new EmpresaModel($id, $Demanda);
        new DonoModel($id, $Demanda);
        new DevModel($id, $Demanda);
        new AreaModel($id, $Demanda);
        new TipoModel($id, $Demanda);
    }
}
