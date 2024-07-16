<?php

namespace App\Models\Api\Demanda\Relatorio;

use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class DevModel extends GeralModel
{
    protected string $ormTabela = TABELA_ANALYTICS_DEMANDA_DEV;
    protected string $campoDemanda = 'id_usuario_equipe';
    protected string $campoBanco = 'id_dev';
    protected string $campoNome = 'dev_nome';
    protected bool $temDemanda = true;

    public function __construct(
        protected int $id,
        protected RelatorioSprintModel $Relatorio
    ) {
        parent::__construct();
    }
}
