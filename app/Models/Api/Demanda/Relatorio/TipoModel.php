<?php

namespace App\Models\Api\Demanda\Relatorio;

use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class TipoModel extends GeralModel
{
    protected string $ormTabela = TABELA_ANALYTICS_DEMANDA_TIPO;
    protected string $campoBanco = 'tipo_valor';
    protected string $campoNome = 'tipo_nome';
    protected bool $temDemanda = false;

    public function __construct(
        protected int $id,
        protected RelatorioSprintModel $Relatorio
    ) {
        parent::__construct();
    }
}
