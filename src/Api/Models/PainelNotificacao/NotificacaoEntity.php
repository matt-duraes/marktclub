<?php

namespace ApiModel\PainelNotificacao;

use ORM\Entity;
use Helpers\EmailHelper;
use System\Classes\PainelNotificacao\Status;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class NotificacaoEntity extends Entity
{
    protected string $_tabela = TABELA_PAINEL_NOTIFICACAO;
    protected array $_insert = [
        'titulo', 'mensagem', 'link', 'botao', 'id_usuario_equipe', 'id_usuario_dono'
    ];
    protected array $_salvar = ['status'];

    protected int $id_usuario_equipe;
    protected int $id_usuario_dono;
    public Status $status;

    public function __construct(
        public ?string $titulo = null,
        public ?string $mensagem = null,
        protected ?string $link = null,
        protected ?string $botao = null,
        protected ?EquipeEntity $Equipe = null,
        protected ?EquipeEntity $Dono = null,
    ) {
        parent::__construct();
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
