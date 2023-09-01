<?php

namespace App\Models\Api\SolicitacaoLoja;

use ORM\Entity;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;
    protected array $ormBuscar = [
        'nome', 'telefone', 'email', 'mensagem', 'data_criacao', 'data_atualizacao', 'origem', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario_cliente', 'nome', 'telefone', 'email', 'mensagem', 'origem'
    ];
    protected array $ormSalvar = ['status'];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio
        telefone|Telefone|valido
        email|Email|valido
        origem|Origem|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_usuario_cliente;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    public string $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public Origem $origem;
    public string $mensagem;
    public string $usuario;

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
