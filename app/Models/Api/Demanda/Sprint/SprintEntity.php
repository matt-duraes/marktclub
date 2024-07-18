<?php

namespace App\Models\Api\Demanda\Sprint;

use ORM\Entity;
use Modules\Data;
use App\Classes\Demanda\Sprint\Status;
use App\Models\Api\Demanda\Dado\MudarStatusModel;
use App\Classes\DemandaDado\Status as StatusDemanda;
use App\Models\Api\Demanda\Relatorio\GerarTodosModel;

final class SprintEntity extends Entity
{
    protected string $ormTabela = TABELA_DEMANDA_SPRINT;
    protected array $ormBuscar = [
        'id_demanda', 'titulo', 'data_inicio', 'data_final', 'texto_inicio', 'texto_final', 'status'
    ];
    protected array $ormInsert = [
        'status' => 1
    ];
    protected array $ormUpdate = [
        'texto_inicio', 'texto_final', 'id_demanda', 'id_demanda_inicio', 'id_demanda_retirada',
        'id_demanda_adicionada', 'data_entrega', 'status'
    ];
    protected array $ormSalvar = [
        'titulo', 'data_inicio', 'data_final'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        data_inicio|Data de inicio|vazio|valido
        data_final|Data final|vazio|valido
    ';
    public string $titulo;
    public Data $data_inicio;
    public Data $data_final;
    public Data $data_entrega;
    public string $texto_inicio;
    public string $texto_final;
    public Status $status;
    protected array $id_demanda;
    protected array $id_demanda_inicio;
    public array $demanda = [];

    public function regraInsert()
    {
        if ($this->existe(['status', 'in', Status::PUBLICADO])) {
            mensagemErro('Erro!', 'Já existe uma sprint em andamento no momento.');
        } elseif ($this->data_inicio->date() < hoje()) {
            mensagemErro('Erro!', 'A data de início da sprint não pode ser menor que hoje.');
        } elseif ($this->data_inicio->date() > dataRemover($this->data_final->date(), 7, 'dias')) {
            mensagemErro('Erro!', 'A data final da sprint deve ter pelo menos 7 dias a mais que a data inicial.');
        }
    }

    public function regraUpdate()
    {
        $mudouStatus = $this->prop('status') != $this->status->numero();
        if ($mudouStatus && $this->status->se(Status::ANDAMENTO)) {
            $this->mudarStatusParaAndamento();
        } elseif ($mudouStatus && $this->status->se([Status::CONCLUIDA_ATRASADA, Status::CONCLUIDA_PRAZO])) {
            $this->mudarStatusParaConcluida();
        } elseif ($mudouStatus && $this->status->se(Status::CANCELADA)) {
            $this->mudarStatusParaCancelada();
        }
    }

    private function mudarStatusParaAndamento()
    {
        if (empty($this->id_demanda)) {
            mensagemErro('Erro!', 'Você deve colocar pelo menos uma demanda na sprint para continuar.');
        }
        $this->id_demanda_inicio = $this->id_demanda;
        new MudarStatusModel($this->id_demanda, new StatusDemanda(StatusDemanda::LIBERADA));
    }

    private function mudarStatusParaConcluida()
    {
        $this->status = new Status(
            $this->data_final->date() >= hoje() ? Status::CONCLUIDA_PRAZO : Status::CONCLUIDA_ATRASADA
        );
        $this->data_entrega = new Data(hoje());
        new GerarTodosModel((int)$this->prop('id'), $this->id_demanda);
    }

    private function mudarStatusParaCancelada()
    {
        new MudarStatusModel($this->id_demanda, new StatusDemanda(StatusDemanda::NOVA));
    }

    protected function regraPosBuscar()
    {
        $this->demanda = $this->id_demanda;
    }
}
