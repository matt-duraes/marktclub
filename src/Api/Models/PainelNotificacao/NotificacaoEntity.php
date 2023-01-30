<?php

namespace ApiModel\PainelNotificacao;

use ORM\Entity;
use Helpers\EmailHelper;
use System\Classes\PainelNotificacao\Status;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class NotificacaoEntity extends Entity
{
    protected string $_tabela = TABELA_PAINEL_NOTIFICACAO;
    protected array $_insert = [
        'titulo', 'mensagem', 'link', 'botao', 'id_usuario_equipe', 'id_usuario_dono', 'target'
    ];
    protected array $_salvar = ['status'];
    protected array $_buscar = [
        'id_usuario_dono', 'titulo', 'mensagem', 'link', 'target', 'botao', 'status'
    ];

    protected int $id_usuario_equipe;
    protected int $id_usuario_dono;
    public Status $status;
    public array $dono;

    public function __construct(
        public ?string $titulo = null,
        public ?string $mensagem = null,
        public ?string $link = null,
        public ?string $target = null,
        public ?string $botao = null,
        protected ?EquipeEntity $Equipe = null,
        protected ?EquipeEntity $Dono = null,
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->dono = (new PerfilModel())->pegarDado($this->id_usuario_dono, true);
    }

    protected function regraInsert()
    {
        $this->id_usuario_equipe = $this->Equipe->get('id');
        $this->id_usuario_dono = $this->Dono->get('id');
        $this->status = new Status(1);
    }

    protected function regraPosInsert()
    {
        if (!eProducao()) {
            return;
        }

        $Email = new EmailHelper(emailEnvio: ['Markt Club', 'nao-resposta@marktclub.com.br']);
        $Email->mensagem(
            titulo: $this->titulo,
            mensagem: $this->mensagem,
            botaoLink: $this->link,
            botaoTexto: $this->botao,
            host: 'markt.club'
        );
        $Email->sendGrid(
            titulo: $this->titulo,
            nome: $this->Equipe->nome,
            email: $this->Equipe->email_trabalho
        );
    }
}
