<?php

namespace App\Models\Api\PainelHistorico;

use Modules\DataHora;
use Helpers\DataHelper;
use App\Models\Api\GeralEntity;
use App\Classes\PainelHistorico\Acao;
use App\Classes\PainelHistorico\Status;

final class HistoricoEntity extends GeralEntity
{
    protected string $_tabela = TABELA_PAINEL_HISTORICO;
    protected array $_insert = [
        'id_relacionado' => '->relacionado',
        'id_usuario_equipe', 'app', 'acao', 'dado'
    ];
    protected array $_salvar = ['mensagem', 'status'];
    protected array $_buscar = [
        'mensagem', 'status', 'data_criacao'
    ];

    protected string $_validarInsert = '
        relacionado|Relacionado|obrigatorio|vazio|isArray
        app|App|obrigatorio|vazio|isArray
        acao|Ação|obrigatorio|vazio
        dado|Dados|array
        status|Status|valido
    ';
    protected string $_validarUpdate = '
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|valido
    ';

    public int $id_usuario_equipe;
    public DataHora $data_criacao;
    public string $mensagem;
    public array $relacionado;
    public array $app;
    public Acao $acao;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->_wherePadrao = ['id_usuario_equipe', $this->idUsuario];
    }

    protected function regraInsert()
    {
        $this->id_usuario_equipe = $this->idUsuario;
        $this->status = new Status(empty($this->mensagem) ? 2 : 1);
    }
    protected function regraUpdate()
    {
        if (!empty($this->prop('mensagem'))) {
            mensagemErro('Erro!', 'Já existe uma mensagem para esse histórico.');
        } else if ($this->prop('status') == 1) {
            mensagemErro(
                'Erro!',
                'Não é mais possível atualizar esse histórico.',
                localhost: 'O status já está com valor 1'
            );
        }
        $this->status = new Status(1);
    }

    protected function regraDestruir()
    {
        $data = (new DataHelper(agora()))->remover(1, 'minuto')->formato('Y-m-d H:i:s');
        if ($data > $this->data_criacao->date()) {
            mensagemErro('Erro!', 'Essa mensagem não pode mais ser deletada.');
        }
    }
}
