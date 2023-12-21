<?php

namespace App\Models\Api\Demanda;

use ApiModel\PainelHistorico\HistoricoEntity;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Status;
use App\Classes\DemandaDado\Tipo;
use App\Models\Api\Demanda\Trait\EmpresaTrait;
use Erro\Erro;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Data;
use Modules\DataHora;
use ORM\Entity;
use System\Classes\PainelHistorico\Acao;

final class DemandaEntity extends Entity
{
    use EmpresaTrait;

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
        'ordem', 'data_entrega_real', 'seguindo'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        tipo|Tipo|vazio|valido
        area|Área|vazio|valido
        status|Status|vazio|valido
        data_entrega|Data da entrega|valido
    ';

    /**
     * @param string $motivo
     *
     * @return void
     * @throws Excecao
     */
    public function cancelar(string $motivo): void
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

    public function seguir(): void
    {
        $id = $this->pegarIdUsuario();
        if (empty($id) || in_array($id, $this->seguindo)) {
            return;
        }
        $this->seguindo[] = $id;
    }

    private function pegarIdUsuario()
    {
        if (!defined('TOKEN') || !array_key_exists('usuario', TOKEN)) {
            return '';
        }
        return TOKEN['usuario']->uuid;
    }

    public function seguirParar(): void
    {
        $id = $this->pegarIdUsuario();
        if (empty($id)) {
            return;
        }
        $seguindo = array_flip($this->seguindo);
        unset($seguindo[$id]);
        $this->seguindo = array_keys($seguindo);
    }

    protected function regraPosBuscar(): void
    {
        $this->equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarUuidPeloId($this->id_usuario_equipe);
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarUuidPeloId($this->id_admin_empresa);
        if ($this->com_prazo->valor() != 'sim') {
            $this->data_entrega = new Data('');
        }
    }

    protected function regraInsert(): void
    {
        $this->com_prazo = new Botao('nao');
        $this->status = new Status(1);
        $this->id_usuario_equipe = TOKEN['usuario']->id;
        $this->ordem = 999;
        $this->pegarIdEmpresa();
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        if ($this->com_prazo->valor() == 'sim' && $this->data_entrega->vazio()) {
            mensagemErro('Campo obrigatório!', 'A data de entrega é obrigatória.');
        }
    }

    /**
     * @return mixed
     * @throws Excecao
     * @throws Erro
     */
    protected function getId(): mixed
    {
        return $this->prop('id');
    }
}
