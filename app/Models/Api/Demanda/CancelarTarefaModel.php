<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;
use App\Classes\DemandaTarefa\Status;

final class CancelarTarefaModel extends ORM
{
    protected string $ormTabela = TABELA_DEMANDA_TAREFA;

    public function __construct(
        private DemandaEntity $Demanda
    ) {
        parent::__construct();
        $salvar = $this
            ->dado([
                'status' => (new Status(Status::CANCELADA))->numero(),
            ])
            ->where([
                ['id_demanda_dado', $this->Demanda->get('id')]
            ])
            ->update();
        if (existeErro($salvar, 'id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao tentar cancelar as tarefas.', status: 500);
        }
    }
}
