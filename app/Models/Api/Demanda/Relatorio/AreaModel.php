<?php

namespace App\Models\Api\Demanda\Relatorio;

use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class AreaModel extends GeralModel
{
    protected string $ormTabela = TABELA_ANALYTICS_DEMANDA_AREA;
    protected string $campoBanco = 'area_valor';
    protected string $campoNome = 'area_nome';
    protected bool $temDemanda = false;

    public function __construct(
        protected int $id,
        protected RelatorioSprintModel $Relatorio
    ) {
        parent::__construct();
    }
}
