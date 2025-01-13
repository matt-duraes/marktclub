<?php

namespace App\Models\Api\SolicitacaoContato;

use ApiModel\PainelNotificacao\NotificacaoEntity;
use App\Classes\SolicitacaoContato\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;
use Throwable;

class SolicitacaoContatoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $local;
    public string $tipo;
    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public string $mensagem;
    public Status $status;
    public array $empresa;
    protected string $ormTabela = TABELA_SOLICITACAO_CONTATO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'local', 'tipo', 'nome', 'email', 'telefone', 'mensagem',
        'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa', 'local', 'tipo', 'nome', 'email', 'telefone', 'mensagem', 'status'
    ];
    protected array $ormUpdate = [
        'status'
    ];
    protected string $ormValidarInsert = '
        local|Local|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio
        nome|Nome|obrigatorio|vazio|valido
        email|E-mail|obrigatorio|vazio|valido
        telefone|Telefone|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
    ';
    protected string $ormValidarUpdate = '
        status|Status|vazio|valido
    ';
    protected int $id_admin_empresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->buscarEmpresa();
    }

    private function buscarEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarPrimeiroRegistro(['id', $this->id_admin_empresa], [
                'uuid', 'nome_fantasia'
            ]);
        $this->empresa = [
            'id'   => $empresa['uuid'],
            'nome' => $empresa['nome_fantasia']
        ];
    }

    protected function regraInsert(): void
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(Status::NOVO);
    }

    protected function regraPosInsert(): void
    {
        try {
            $ormHelper = new OrmHelper(TABELA_USUARIO_EQUIPE);
            $usuarios = $ormHelper->pegarListaCampo([
                ['id_admin_empresa', $this->id_admin_empresa],
                ['permissao', 'json', 'solicitacao_contato_visualizar']
            ], 'id');

            foreach ($usuarios as $idUsuario) {
                $Equipe = new EquipeEntity();
                $Dono = new EquipeEntity();

                $Equipe->buscar(['id', $idUsuario]);
                $Dono->buscar(['id', $idUsuario]);

                $NotificacaoEntity = new NotificacaoEntity(
                    'Uma nova solicitação de contato foi registrada.',
                    'O Usuário ' . $this->nome . ' deseja entrar em contato com o clube',
                    LINK_PAINEL . '/app/visualizar/solicitacao-contato/' . $this->id,
                    '_blank',
                    'Ver solicitação de contato',
                    $Equipe,
                    $Dono
                );
                $NotificacaoEntity->salvar();
            }
        } catch (Throwable) {
        }
    }
}
