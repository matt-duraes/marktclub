<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Status;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Classes\DemandaDado\Status as DemandaDadoStatus;

final class TarefaEntity extends Entity
{
    protected string $_tabela = TABELA_DEMANDA_TAREFA;
    protected array $_buscar = [
        'minuto_producao_estimada', 'titulo', 'texto', 'status', 'tipo', 'id_usuario_equipe', 'minuto_producao_real',
        'data_producao_inicio', 'data_producao_final', 'id_demanda_dado'
    ];

    protected array $_insert = [
        'id_demanda_dado'
    ];
    protected array $_salvar = [
        'minuto_producao_estimada', 'id_usuario_equipe', 'status', 'titulo', 'texto', 'tipo',
        'data_producao_inicio', 'data_producao_final', 'minuto_producao_real'
    ];
    protected string $_validarInsert = '
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

    protected function regraPosUpdate()
    {
        if ($this->status->indice() == 'andamento') {
            $this->Demanda->status = new DemandaDadoStatus('andamento');
            $this->Demanda->salvar();
        } else if (
            $this->status->indice() == 'concluida' &&
            (new TarefaModel($this->Demanda))->verificarSeTodasAsTarefasEstaoConcluidas()
        ) {
            $this->Demanda->status = new DemandaDadoStatus('teste');
            $this->Demanda->salvar();
        }
    }

    private function pegarDemanda($id)
    {
        try {
            $Demanda = new DemandaEntity();
            if (is_int($id)) {
                $Demanda->_id($id);
            } else {
                $Demanda->id($id);
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
            is_int($id) ? $Equipe->_id($id) : $Equipe->id($id);
            $this->equipe = $Equipe;
        } catch (\Throwable $e) {
            mensagemErro('Erro!', 'Não foi encontrado nenhum usuário pelo id enviado.', status: 404);
        }
    }

    public function getId()
    {
        return $this->prop('id');
    }
}
