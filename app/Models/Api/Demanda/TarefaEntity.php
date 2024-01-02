<?php

namespace App\Models\Api\Demanda;

use ApiModel\PainelHistorico\HistoricoEntity;
use App\Classes\DemandaTarefa\Status;
use App\Classes\DemandaTarefa\Tipo;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use Erro\Erro;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\DataHora;
use ORM\Entity;
use System\Classes\PainelHistorico\Acao;

final class TarefaEntity extends Entity
{
    public Status $status;
    public int $id_demanda_dado;
    public int $id_usuario_equipe;
    public DataHora $data_producao_inicio;
    public DataHora $data_producao_final;
    public int $minuto_producao_real;
    public int $minuto_producao_estimada;
    public array $teste;
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
    protected array $like;
    private OrmHelper $OrmEquipe;

    public function __construct(
        private readonly ?string $demanda = null,
        public ?string $titulo = null,
        public ?string $texto = null,
        public ?Tipo $tipo = null,
        public ?string $equipe = null
    ) {
        parent::__construct();
        $this->OrmEquipe = new OrmHelper(TABELA_USUARIO_EQUIPE);
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    public function getId(): mixed
    {
        return $this->prop('id');
    }

    /**
     * @throws Excecao
     */
    public function like(): void
    {
        $id = TOKEN['usuario']->uuid;
        if (in_array($id, $this->like)) {
            return;
        }
        $this->like[] = $id;
        $this->salvar();
    }

    /**
     * @param string $motivo
     *
     * @throws Excecao
     */
    public function deslike(string $motivo): void
    {
        $this->status = new Status(Status::AGUARDANDO);
        $this->like = [];
        $this->salvar();

        $Demanda = $this->pegarDemanda();

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

    /**
     * @return DemandaEntity
     */
    private function pegarDemanda(): DemandaEntity
    {
        $Demanda = new DemandaEntity();
        $Demanda->id($this->id_demanda_dado);
        return $Demanda;
    }

    protected function regraPosBuscar(): void
    {
        if (!empty($this->id_usuario_equipe)) {
            $this->equipe = $this->OrmEquipe->pegarUuidPeloId($this->id_usuario_equipe);
        }
    }

    protected function regraInsert(): void
    {
        $this->id_demanda_dado = (new OrmHelper(TABELA_DEMANDA_DADO))
            ->pegarIdPeloUuid($this->demanda);
        $this->status = new Status(Status::AGUARDANDO);
        $this->minuto_producao_real = 0;
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    protected function regraUpdate(): void
    {
        $this->mudarStatus();
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    private function mudarStatus(): void
    {
        $statusAtual = (new Status($this->prop('status')))->indice();
        $statusNovo = $this->status->indice();
        if ($statusNovo == Status::ANDAMENTO && $this->data_producao_inicio->vazio()) {
            $this->data_producao_inicio = new DataHora(agora());
        }
        if ($statusNovo == Status::CONCLUIDA && $this->data_producao_final->vazio()) {
            $this->data_producao_final = new DataHora(agora());
        }
        if ($statusAtual == Status::AGUARDANDO && $statusNovo == Status::ANDAMENTO) {
            $this->id_usuario_equipe = TOKEN['usuario']->id;
        }
    }
}
