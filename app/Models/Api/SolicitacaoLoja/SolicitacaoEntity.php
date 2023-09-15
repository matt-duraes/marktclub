<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

final class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public Origem $origem;
    public string $mensagem;
    public string $usuario;
    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;
    protected array $ormBuscar = [
        'nome', 'telefone', 'email', 'mensagem',
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
        nome|Nome|obrigatorio|vazio|valido
        email|Email|obrigatorio|vazio|valido
        telefone|Telefone|valido
        mensagem|Mensagem|obrigatorio|vazio
        origem|Origem|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_usuario_cliente;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

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
        if ($this->propriedadeExiste('usuario') && !empty($this->usuario)) {
            $this->id_usuario_cliente = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarIdPeloUuid($this->usuario);
        }
        $this->status = new Status(Status::NOVO);
    }
}
