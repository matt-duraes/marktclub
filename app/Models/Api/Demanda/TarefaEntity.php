<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Status;
use System\Classes\PainelHistorico\Acao;
use ApiModel\PainelHistorico\HistoricoEntity;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Classes\DemandaDado\Status as DemandaDadoStatus;

final class TarefaEntity extends Entity
{
    protected string $ormTabela = TABELA_DEMANDA_TAREFA;
    protected array $ormBuscar = [
        'minuto_producao_estimada', 'titulo', 'texto', 'status', 'tipo', 'id_usuario_equipe', 'minuto_producao_real',
        'data_producao_inicio', 'data_producao_final', 'id_demanda_dado', 'like'
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
    private DemandaEntity $Demanda;
    protected array $like;
    public array $teste;

    public function __construct(
        private ?string $demanda = null,
        public ?string $titulo = null,
        public ?string $texto = null,
        public ?Tipo $tipo = null,
        public ?int $minuto_producao_estimada = null,
        public null|string|EquipeEntity $equipe = null
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->pegarDemanda($this->id_demanda_dado);
        if (!empty($this->id_usuario_equipe)) {
            $this->pegarUsuarioEquipe($this->id_usuario_equipe);
        }
        if (!empty($this->like)) {
            $Perfil = new PerfilModel();
            $this->teste[] = $Perfil->pegarLista($this->like);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->pegarDemanda($this->demanda);

        $this->id_demanda_dado = $this->Demanda->get('id');
        $this->status = new Status(1);
        $this->minuto_producao_real = 0;
        if (!empty($this->equipe)) {
            $this->pegarUsuarioEquipe($this->equipe);
            $this->id_usuario_equipe = $this->equipe->get('id');
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

    protected function regraPosUpdate()
    {
        if ($this->status->indice() == 'andamento') {
            $this->Demanda->status = new DemandaDadoStatus('andamento');
            $this->Demanda->salvar();
        } elseif (
            $this->status->indice() == 'concluida' &&
            (new TarefaModel($this->Demanda))->verificarSeTodasAsTarefasEstaoConcluidas()
        ) {
            $this->Demanda->status = new DemandaDadoStatus('teste');
            $this->Demanda->salvar();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DEMAIS MÉTODOS
    |--------------------------------------------------------------------------
    */
    private function pegarDemanda($id)
    {
        try {
            $Demanda = new DemandaEntity();
            if (is_int($id)) {
                $Demanda->id($id);
            } else {
                $Demanda->uuid($id);
            }
            $this->Demanda = $Demanda;
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhuma demanda pelo id enviado.', status: 404);
        }
    }

    private function pegarUsuarioEquipe($id)
    {
        try {
            $Equipe = new EquipeEntity(validarToken: false);
            is_int($id) ? $Equipe->id($id) : $Equipe->uuid($id);
            $this->equipe = $Equipe;
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhum usuário pelo id enviado.', status: 404);
        }
    }

    public function getId()
    {
        return $this->prop('id');
    }

    public function like()
    {
        $id = TOKEN['usuario']->get('id');
        if (in_array($id, $this->like)) {
            return;
        }
        $this->like[] = $id;
        $this->salvar();
        $Tarefa = new TarefaModel($this->Demanda);
        $Tarefa->verificarSePodeConcluirTarefa();
    }

    public function deslike(string $motivo)
    {
        $this->status = new Status('andamento');
        $this->like = [];
        $this->salvar();

        $this->Demanda->status = new DemandaDadoStatus('andamento');
        $this->Demanda->salvar();

        $Perfil = new PerfilModel();
        $usuario = $Perfil->pegarDado($this->id_usuario_equipe);

        $Historico = new HistoricoEntity();
        $Historico->mensagem = 'Tarefa recusada: ' . $this->titulo . '<br>' . $motivo;
        $Historico->relacionado = [$this->Demanda->id];
        $Historico->app = ['demanda_dado'];
        $Historico->acao = new Acao('mensagem');
        $Historico->notificar_equipe = [$usuario['id']];
        $Historico->notificar_titulo = 'Recusou sua tarefa, acesse a demanda para verificar o motivo.';
        $Historico->notificar_link = LINK_PAINEL . '/demanda/'
            . $this->Demanda->area->indice() . '#demanda-' . $this->Demanda->id;
        $Historico->salvar();
    }
}
