<?php

namespace ApiModel\PainelHistorico;

use ORM\Entity;
use Helpers\DataHelper;
use System\Classes\PainelHistorico\Acao;
use System\Classes\PainelHistorico\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use ApiModel\PainelNotificacao\NotificacaoEntity;

final class HistoricoEntity extends Entity
{
    use ValidarEmpresaTrait;

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
    public string $mensagem;
    public array $relacionado;
    public array $app;
    public Acao $acao;
    public Status $status;

    private array $usuarioNotificado = [];
    public string $notificar_titulo = '';
    public string $notificar_link = '';
    public array $notificar_equipe = [];

    private int $idUsuario;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function regraInsert()
    {
        $this->id_usuario_equipe = $this->idUsuario;
        $this->status = new Status(empty($this->mensagem) ? Status::SEM_MENSAGEM : Status::COM_MENSAGEM);
    }
    protected function regraPosInsert()
    {
        if ($this->notificar_equipe) {
            $this->enviarNotificacaoParaUsuario(
                !empty($this->notificar_titulo) ? $this->notificar_titulo : 'Fez um comentário',
                $this->notificar_equipe,
                'uuid'
            );
        }

        preg_match_all("/@[a-z0-9\.]{1,}/", $this->mensagem, $usuario);
        if (array_key_exists(0, $usuario) && $usuario[0]) {
            $this->enviarNotificacaoParaUsuario('Marcou você em um comentário', $usuario[0], 'nome_perfil');
        }
    }
    private function enviarNotificacaoParaUsuario(string $titulo, array $usuario, string $campo)
    {
        try {
            $Dono = new EquipeEntity(validarToken: false);
            $Dono->_id($this->id_usuario_equipe);
        } catch (\Throwable) {
            return;
        }
        foreach ($usuario as $valor) {
            $valor = str_replace('@', '', $valor);
            try {
                $Equipe = new EquipeEntity(validarToken: false);
                $Equipe->buscar([$campo, $valor]);
            } catch (\Throwable) {
                continue;
            }

            $idEquipe = $Equipe->get('id');
            if (in_array($idEquipe, $this->usuarioNotificado) || $idEquipe == $this->id_usuario_equipe) {
                continue;
            }
            $this->usuarioNotificado[] = $idEquipe;

            try {
                $Notificacao = new NotificacaoEntity(
                    titulo: $titulo,
                    mensagem: nl2br($this->mensagem),
                    link: $this->notificar_link,
                    botao: 'Ver comentário',
                    Equipe: $Equipe,
                    Dono: $Dono,
                );
                $Notificacao->salvar();
            } catch (\Throwable) {
                continue;
            }
        }
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
