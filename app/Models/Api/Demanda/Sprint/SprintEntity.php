<?php

namespace App\Models\Api\Demanda\Sprint;

use ORM\Entity;
use Modules\Data;
use Helpers\OrmHelper;
use App\Classes\Demanda\Sprint\Status;
use App\Classes\DemandaDado\Status as StatusDemanda;

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
        'id_demanda_adicionada', 'status'
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
    public string $texto_inicio;
    public string $texto_final;
    public Status $status;
    protected array $id_demanda;
    protected array $id_demanda_inicio;
    public array $demanda = [];

    public function regraInsert()
    {
        if ($this->existe(['status', new Status(Status::ANDAMENTO)])) {
            mensagemErro('Erro!', 'Já existe uma sprint em andamento no momento.');
        }
    }

    public function regraUpdate()
    {
        $mudouStatus = $this->prop('status') != $this->status->numero();
        if ($mudouStatus && $this->status->se(Status::ANDAMENTO)) {
            $this->mudarStatusParaAndamento();
        } elseif ($mudouStatus && $this->status->se([Status::CONCLUIDA_ATRASADA, Status::CONCLUIDA_PRAZO])) {
            $this->mudarStatusParaConcluido();
        }
    }

    private function mudarStatusParaAndamento()
    {
        if (empty($this->id_demanda)) {
            mensagemErro('Erro!', 'Você deve colocar pelo menos uma demanda na sprint para continuar.');
        }
        $this->id_demanda_inicio = $this->id_demanda;
        $Demanda = new OrmHelper(TABELA_DEMANDA_DADO);
        $Demanda
            ->dado([
                'status' => new StatusDemanda(StatusDemanda::LIBERADA)
            ])
            ->where([
                ['uuid', 'in', $this->id_demanda]
            ])
            ->update();
    }

    private function mudarStatusParaConcluido()
    {
        $this->status = new Status(
            $this->data_final->date() >= hoje() ? Status::CONCLUIDA_PRAZO : Status::CONCLUIDA_ATRASADA
        );
    }

    public function regraPosBuscar()
    {
        $this->demanda = $this->id_demanda;
    }
}
