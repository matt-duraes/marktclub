<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use Modules\Data;
use Modules\Botao;
use Modules\DataHora;
use Helpers\OrmHelper;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Status;
use System\Classes\PainelHistorico\Acao;
use ApiModel\PainelHistorico\HistoricoEntity;
use App\Models\Api\Demanda\Trait\EquipeTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;

final class DemandaEntity extends Entity
{
    use EquipeTrait;
    use EmpresaTrait;

    protected string $ormTabela = TABELA_DEMANDA_DADO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_equipe', 'titulo', 'tipo', 'status', 'seguindo',
        'arquivo', 'com_prazo', 'data_entrega', 'area', 'data_criacao'
    ];
    protected array $ormInsert = [
        'tipo', 'area'
    ];
    protected array $ormSalvar = [
        'arquivo', 'id_admin_empresa', 'id_usuario_equipe', 'titulo', 'status', 'com_prazo', 'data_entrega',
        'ordem', 'data_entrega_real'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        tipo|Tipo|vazio|valido
        area|Área|vazio|valido
        status|Status|vazio|valido
        data_entrega|Data da entrega|valido
    ';
    public array $arquivo = [];
    public string $equipe;
    public array $seguindo = [];
    public Botao $com_prazo;
    public Data $data_entrega;
    public DataHora $data_entrega_real;
    public int $ordem;
    public int $id_admin_empresa;
    public Status $status;
    public int $id_usuario_equipe;
    public string $titulo;
    public string|array $empresa;
    public Tipo $tipo;
    public Area $area;

    public function __construct()
    {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR
    |--------------------------------------------------------------------------
    */
    protected function regraPosBuscar()
    {
        $this->equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarUuidPeloId($this->id_usuario_equipe);
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarUuidPeloId($this->id_admin_empresa);
        if ($this->com_prazo->valor() != 'sim') {
            $this->data_entrega = new Data('');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->com_prazo = new Botao('nao');
        $this->status = new Status(1);
        $this->id_usuario_equipe = TOKEN['usuario']->id;
        $this->ordem = 999;
        $this->pegarIdEmpresa();
    }

    protected function regraSalvar()
    {
        if ($this->com_prazo->valor() == 'sim' && $this->data_entrega->vazio()) {
            mensagemErro('Campo obrigatório!', 'A data de entrega é obrigatória.');
        }
    }

    protected function getId()
    {
        return $this->prop('id');
    }

    /*
    |--------------------------------------------------------------------------
    | CANCELAR
    |--------------------------------------------------------------------------
    */
    public function cancelar(string $motivo)
    {
        new CancelarTarefaModel(Demanda: $this);

        $this->status = new Status(Status::CANCELADA);
        $this->salvar();

        $Historico = new HistoricoEntity();
        $Historico->mensagem = 'Tarefa cancelada: <br>' . $motivo;
        $Historico->relacionado = [$this->id];
        $Historico->app = ['demanda_dado'];
        $Historico->acao = new Acao('mensagem');
        $Historico->salvar();
    }
}
