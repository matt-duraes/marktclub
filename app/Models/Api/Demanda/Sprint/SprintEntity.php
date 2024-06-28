<?php

namespace App\Models\Api\Demanda\Sprint;

use ORM\Entity;
use Modules\Data;
use App\Classes\Demanda\Sprint\Status;

final class SprintEntity extends Entity
{
    protected string $ormTabela = TABELA_DEMANDA_SPRINT;
    protected array $ormBuscar = [
        'titulo', 'data_inicio', 'data_final', 'texto_inicio', 'texto_final', 'status'
    ];
    protected array $ormInsert = [
        'status' => 1
    ];
    protected array $ormUpdate = [
        'texto_inicio', 'texto_final', 'id_demanda', 'id_demanda_inicio', 'id_demanda_retirada',
        'id_demanda_adicionada'
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

    public function regraInsert()
    {
        if ($this->existe(['status', new Status(Status::ANDAMENTO)])) {
            mensagemErro('Erro!', 'Já existe uma sprint em andamento no momento.');
        }
    }
}
