<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use Modules\DataHora;
use Helpers\OrmHelper;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Status;
use System\Classes\PainelHistorico\Acao;
use ApiModel\PainelHistorico\HistoricoEntity;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use App\Classes\DemandaDado\Status as DemandaDadoStatus;

final class TarefaEntity extends Entity
{
    protected string $ormTabela = TABELA_DEMANDA_TAREFA;
    protected array $ormBuscar = [
        'minuto_producao_estimada', 'titulo', 'texto', 'status', 'tipo', 'id_usuario_equipe', 'minuto_producao_real',
        'data_producao_inicio', 'data_producao_final', 'id_demanda_dado', 'like', 'data_criacao'
    ];
    protected array $ormInsert = [
        'id_demanda_dado'
    ];
    protected array $ormSalvar = [
        'minuto_producao_estimada', 'id_usuario_equipe', 'status', 'titulo', 'texto', 'tipo',
        'data_producao_inicio', 'data_producao_final', 'minuto_producao_real', 'like'
    ];
    protected string $ormValidarInsert = '
        titulo|Titulo|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|valido
        minuto_producao_estimada|Tempo de produção|int
    ';
    public Status $status;
    public int $id_demanda_dado;
    public int $id_usuario_equipe;
    public DataHora $data_producao_inicio;
    public DataHora $data_producao_final;
    public int $minuto_producao_real;
    protected array $like;
    public array $teste;
    private OrmHelper $OrmEquipe;

    public function __construct(
        private ?string $demanda = null,
        public ?string $titulo = null,
        public ?string $texto = null,
        public ?Tipo $tipo = null,
        public ?string $equipe = null
    ) {
        parent::__construct();
        $this->OrmEquipe = new OrmHelper(TABELA_USUARIO_EQUIPE);
    }

    protected function regraPosBuscar()
    {
        if (!empty($this->id_usuario_equipe)) {
            $this->equipe = $this->OrmEquipe->pegarUuidPeloId($this->id_usuario_equipe);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->id_demanda_dado = (new OrmHelper(TABELA_DEMANDA_DADO))->pegarIdPeloUuid($this->demanda);
        $this->status = new Status(Status::AGUARDANDO);
        $this->minuto_producao_real = 0;
        if (!empty($this->equipe)) {
            $this->id_usuario_equipe = $this->OrmEquipe->pegarIdPeloUuid($this->equipe);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA UPDATE
    |--------------------------------------------------------------------------
    */
    protected function regraUpdate()
    {
        if ($this->status->indice() == 'andamento' && $this->data_producao_inicio->vazio()) {
            $this->data_producao_inicio = new DataHora(agora());
        }
        if ($this->status->indice() == 'concluida' && $this->data_producao_final->vazio()) {
            $this->data_producao_final = new DataHora(agora());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DEMAIS MÉTODOS
    |--------------------------------------------------------------------------
    */
    public function getId()
    {
        return $this->prop('id');
    }

    private function pegarDemanda()
    {
        $Demanda = new DemandaEntity();
        $Demanda->id($this->id_demanda_dado);
        return $Demanda;
    }

    public function like()
    {
        $id = TOKEN['usuario']->id;
        if (in_array($id, $this->like)) {
            return;
        }
        $this->like[] = $id;
        $this->salvar();
    }

    public function deslike(string $motivo)
    {
        $this->status = new Status('andamento');
        $this->like = [];
        $this->salvar();

        $Demanda = $this->pegarDemanda();
        $Demanda->status = new DemandaDadoStatus('andamento');
        $Demanda->salvar();

        $Perfil = new PerfilModel();
        $usuario = $Perfil->pegarDado($this->id_usuario_equipe);

        $Historico = new HistoricoEntity();
        $Historico->mensagem = 'Tarefa recusada: ' . $this->titulo . '<br>' . $motivo;
        $Historico->relacionado = [$Demanda->id];
        $Historico->app = ['demanda_dado'];
        $Historico->acao = new Acao('mensagem');
        $Historico->notificar_equipe = [$usuario['id']];
        $Historico->notificar_titulo = 'Recusou sua tarefa, acesse a demanda para verificar o motivo.';
        $Historico->notificar_link = LINK_PAINEL . '/demanda/'
            . $Demanda->area->indice() . '#demanda-' . $Demanda->id;
        $Historico->salvar();
    }
}
