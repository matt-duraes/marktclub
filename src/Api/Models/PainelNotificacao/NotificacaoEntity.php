<?php

namespace ApiModel\PainelNotificacao;

use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use Erro\Excecao;
use Helpers\EmailHelper;
use ORM\Entity;
use SendGrid\Mail\TypeException;
use System\Classes\PainelNotificacao\Status;

final class NotificacaoEntity extends Entity
{
    public Status $status;
    public array $dono;
    protected string $ormTabela = TABELA_PAINEL_NOTIFICACAO;
    protected array $ormBuscar = [
        'id_usuario_dono', 'titulo', 'mensagem', 'link', 'target', 'botao',
        'target', 'status'
    ];
    protected array $ormInsert = [
        'id_usuario_equipe', 'id_usuario_dono', 'titulo', 'mensagem',
        'link', 'botao', 'target'
    ];
    protected array $ormSalvar = [
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

    protected function regraPosBuscar(): void
    {
        $this->dono = (new PerfilModel())->pegarDado($this->id_usuario_dono);
        $this->target = $this->target == '_blank' ? '_blank' : '_self';
    }

    protected function regraInsert(): void
    {
        $this->id_usuario_equipe = $this->Equipe->get('id');
        $this->id_usuario_dono = $this->Dono->get('id');
        $this->status = new Status(Status::NOVO);
    }

    /**
     * @return void
     * @throws Excecao
     * @throws TypeException
     */
    protected function regraPosInsert(): void
    {
        if (eLocalhost()) {
            return;
        }

        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: $this->titulo,
            mensagem: $this->mensagem,
            botaoTexto: $this->botao,
            botaoLink: $this->link
        );
        $Email->sendGrid(
            $this->titulo,
            $this->Equipe->nome,
            $this->Equipe->email_trabalho,
            deNome: 'Painel Administratrivo',
            deEmail: 'nao-resposta@youhuul.com.br'
        );
    }
}
