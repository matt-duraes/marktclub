<?php

namespace ApiModel\PainelNotificacao;

use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use Erro\Excecao;
use Helpers\EmailHelper;
use ORM\Entity;
use System\Classes\PainelNotificacao\Status;

final class NotificacaoEntity extends Entity
{
    public Status $status;
    public array $dono;
    protected string $ormTabela = TABELA_PAINEL_NOTIFICACAO;
    protected array $ormInsert = [
        'titulo',
        'mensagem',
        'link',
        'botao',
        'id_usuario_equipe',
        'id_usuario_dono',
        'target'
    ];
    protected array $ormSalvar = ['status'];
    protected array $ormBuscar = [
        'id_usuario_dono',
        'titulo',
        'mensagem',
        'link',
        'target',
        'botao',
        'target',
        'status'
    ];
    protected int $id_usuario_equipe;
    protected int $id_usuario_dono;

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
        $this->target = $this->target == '_blank' ? '_blank' : '_self';
    }

    /**
     * @throws Excecao
     */
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
            botaoTexto: $this->botao,
            botaoLink: $this->link,
            host: 'markt.club'
        );
        $Email->sendGrid(
            titulo: $this->titulo,
            nome: $this->Equipe->nome,
            email: $this->Equipe->email_trabalho
        );
    }
}
