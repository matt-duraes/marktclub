<?php

namespace App\Models\Api\SolicitacaoLoja;

use Erro\Erro;
use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Cpf $cpf;
    public string $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public Origem $origem;
    public string $mensagem;
    public string $usuario;
    public array $quem_indicou;
    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome', 'telefone', 'email', 'mensagem',
        'origem', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario_cliente', 'nome', 'telefone', 'email', 'mensagem', 'origem'
    ];
    protected array $ormSalvar = [
        'status'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio
        email|Email|obrigatorio|vazio|valido
        telefone|Telefone|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
        origem|Origem|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    private ?int $idUsuario = null;
    protected ?int $id_usuario_cliente;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        $this->setarIdUsuario();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $ormHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        if ($this->propriedadeExiste('usuario') && !empty($this->usuario)) {
            $this->id_usuario_cliente = $ormHelper->pegarIdPeloUuid($this->usuario);
        } elseif ($this->propriedadeExiste('cpf') && $this->cpf->valido()) {
            $this->id_usuario_cliente = $ormHelper->pegarCampoPor('id', ['documento', $this->cpf->numero()]);
        }
        $this->status = new Status(Status::NOVO);
    }

    /**
     * @throws Excecao|Erro
     */
    public function regraPosBuscar(): void
    {
        $this->setarQuemIndicou();
    }

    /**
     * @throws Excecao|Erro
     */
    private function setarQuemIndicou(): void
    {
        $Usuario = new ClienteEntity(validarToken: false);
        $Usuario->buscar([
            ['id', $this->prop('id_usuario_cliente')],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if (empty($Usuario->id)) {
            return;
        }

        $this->quem_indicou = [
            'id'    => $Usuario->id,
            'nome'  => $Usuario->nome->nome(),
            'cpf'   => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email()
        ];
    }
}
