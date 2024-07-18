<?php

namespace App\Models\Api\Demanda\Relatorio;

use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class EmpresaModel extends GeralModel
{
    protected string $ormTabela = TABELA_ANALYTICS_DEMANDA_EMPRESA;
    protected string $campoDemanda = 'id_admin_empresa';
    protected string $campoBanco = 'id_admin_empresa';
    protected string $campoNome = 'empresa_nome';
    protected bool $temDemanda = true;

    public function __construct(
        protected int $id,
        protected RelatorioSprintModel $Relatorio
    ) {
        parent::__construct();
    }
}
