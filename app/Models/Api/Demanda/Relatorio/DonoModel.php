<?php

namespace App\Models\Api\Demanda\Relatorio;

use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class DonoModel extends GeralModel
{
    protected string $ormTabela = TABELA_ANALYTICS_DEMANDA_DONO;
    protected string $campoDemanda = 'id_usuario_equipe';
    protected string $campoBanco = 'id_dono';
    protected string $campoNome = 'dono_nome';
    protected bool $temDemanda = true;

    public function __construct(
        protected int $id,
        protected RelatorioSprintModel $Relatorio
    )
    {
        parent::__construct();
    }
}
