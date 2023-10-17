<?php

namespace App\Models\Api\SolicitacaoContato;

use App\Classes\SolicitacaoContato\Status;
use App\Models\Api\SolicitacaoContato\Trait\ConstrutorTrait;
use Helpers\OrmHelper;
use Http\Request;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class SolicitacaoContatoEntity extends Entity
{
    use ConstrutorTrait;

    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public string $mensagem;
    public string $url;
    public Status $status;
    public array $empresa;
    protected string $ormTabela = TABELA_SOLICITACAO_CONTATO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'nome', 'email',
        'telefone', 'mensagem', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'nome', 'email', 'telefone', 'mensagem', 'status'
    ];
    protected array $ormUpdate = [
        'status'
    ];
    protected string $ormValidarInsert = '
        nome|Nome|obrigatorio|vazio|valido
        email|E-mail|obrigatorio|vazio|valido
        telefone|Telefone|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
    ';
    protected string $ormValidarUpdate = '
        status|Status|vazio|valido
    ';
    protected ?int $idEmpresa;

    public function __construct(
        protected readonly ?Request $request = null
    ) {
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->buscarIdEmpresa();
        $this->status = new Status(Status::NOVO);
    }

    public function regraPosBuscar(): void
    {
        if (empty($this->id_admin_empresa)) {
            $this->empresa = [
                'id'   => '',
                'nome' => ''
            ];
            return;
        }

        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarPrimeiroRegistro(['id', $this->id_admin_empresa], [
                'uuid', 'nome_fantasia'
            ]);
        $this->empresa = [
            'id'   => $empresa['uuid'],
            'nome' => $empresa['nome_fantasia']
        ];
    }
}
