<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\DemandaTrabalho\Status;
use App\Classes\DemandaTarefa\Status as DemandaTarefaStatus;

final class TrabalhoEntity extends Entity
{
    protected string $ormTabela = TABELA_DEMANDA_TRABALHO;
    protected array $ormInsert = ['id_demanda_tarefa', 'id_usuario_equipe', 'minuto_trabalhado'];
    protected array $ormSalvar = ['status', 'data_trabalho', 'minuto_trabalhado'];
    protected array $ormBuscar = ['id_demanda_tarefa', 'data_trabalho', 'data_criacao'];
    protected int $id_demanda_tarefa;
    protected int $id_usuario_equipe;
    protected Status $status;
    public string $acao = '';
    protected DataHora $data_trabalho;
    private TarefaEntity $Tarefa;
    public int $tempo_trabalho;
    public int $tempo_total;
    public int $minuto_trabalhado;

    public function __construct(
        private ?string $tarefa = null
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->pegarTarefa($this->id_demanda_tarefa);
        $this->tempo_trabalho = $this->Tarefa->minuto_producao_real;
        $this->tempo_total = $this->Tarefa->minuto_producao_estimada;
    }

    protected function regraInsert()
    {
        $this->pegarTarefa($this->tarefa);

        $Trabalho = new TrabalhoModel();
        $Trabalho->darBaixaTrabalhoAntigos($this->Tarefa->get('id'));
        $Trabalho->atualizarTempoTotalTrabalho($this->Tarefa->get('id'));

        $this->minuto_trabalhado = 0;
        $this->id_demanda_tarefa = $this->Tarefa->get('id');
        $this->id_usuario_equipe = TOKEN['usuario']->get('id');
        $this->data_trabalho = new DataHora(agora());
        $this->status = new Status(1);
    }

    protected function regraPosInsert()
    {
        $this->Tarefa->status = new DemandaTarefaStatus('andamento');
        $this->Tarefa->id_usuario_equipe = TOKEN['usuario']->get('id');
        $this->Tarefa->salvar();
    }

    protected function regraUpdate()
    {
        $acao = $this->acao;
        if (in_array($acao, ['parar', 'concluir'])) {
            $this->status = new Status(2);
        }
        if (in_array($acao, ['atualizar', 'parar', 'concluir'])) {
            $this->data_trabalho = new DataHora(agora());
            $minuto = dataDiferencaMinuto($this->data_criacao->date(), agora());
            $this->minuto_trabalhado = $minuto;
        }
    }

    public function regraPosUpdate()
    {
        $acao = $this->acao;
        if ($acao == 'concluir') {
            $this->Tarefa->status = new DemandaTarefaStatus('concluida');
            $this->Tarefa->salvar();
        }
        if (in_array($acao, ['parar', 'concluir'])) {
            $Trabalho = new TrabalhoModel();
            $Trabalho->atualizarTempoTotalTrabalho($this->id_demanda_tarefa);
        }
    }

    private function pegarTarefa($id)
    {
        try {
            $this->Tarefa = new TarefaEntity();
            if (is_int($id)) {
                $this->Tarefa->id($id);
            } else {
                $this->Tarefa->uuid($id);
            }
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhuma tarefa pelo id enviado.', status: 404);
        }
    }
}
