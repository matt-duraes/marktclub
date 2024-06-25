<?php

namespace App\Models\Api\SolicitacaoContato;

use ORM\Entity;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\SolicitacaoContato\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class SolicitacaoContatoEntity extends Entity
{
    use ValidarEmpresaTrait;

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
    private int $idEmpresa;
    protected int $id_admin_empresa;
    public string $local;
    public string $tipo;
    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public string $mensagem;
    public Status $status;
    public array $empresa;

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(Status::NOVO);
    }

    public function regraPosBuscar(): void
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
}
