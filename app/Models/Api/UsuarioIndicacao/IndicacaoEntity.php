<?php

namespace App\Models\Api\UsuarioIndicacao;

use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Erro;
use Erro\Excecao;
use Modules\Email;
use Modules\Telefone;
use ORM\Entity;
use Throwable;

final class IndicacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Email $email;
    public Telefone $telefone;
    public array $quem_indicou = [];
    public array $usuario_ativo = [];
    public string $usuario;
    public Status $status;
    public string $hash;
    protected string $ormTabela = TABELA_USUARIO_INDICACAO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome', 'email', 'telefone',
        'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa', 'id_usuario_cliente', 'hash',
        'nome', 'email', 'telefone', 'status'
    ];
    protected array $ormUpdate = [
        'status'
    ];
    protected ?int $idEmpresa;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->hash = uuid();
        $this->status = new Status(Status::INDICADO);
        $this->setarUsuarioQueIndicou();
    }

    /**
     * @throws Excecao
     */
    private function setarUsuarioQueIndicou(): void
    {
        try {
            $Usuario = new ClienteEntity();
            $Usuario->uuid($this->usuario);
            $this->id_usuario_cliente = $Usuario->get('id');
        } catch (Throwable) {
            mensagemErro('Erro!', 'Usuário enviado não foi encontrado');
        }
    }

    /**
     * @throws Excecao|Erro
     */
    protected function regraPosBuscar(): void
    {
        $this->setarQuemIndicou();
        $this->setarUsuarioAtivado();
    }

    protected function setarQuemIndicou(): void
    {
        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['id', $this->id_usuario_cliente],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if (empty($Usuario->id)) {
            return;
        }

        $this->quem_indicou = [
            'id'    => $Usuario->id,
            'nome'  => $Usuario->nome,
            'cpf'   => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email()
        ];
    }

    /**
     * @throws Excecao|Erro
     */
    protected function setarUsuarioAtivado(): void
    {
        if ($this->status->indice() !== Status::ATIVADO) {
            return;
        }

        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['id_usuario_indicacao', $this->prop('id')],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if (empty($Usuario->id)) {
            return;
        }

        $this->usuario_ativo = [
            'id'    => $Usuario->id,
            'nome'  => $Usuario->nome,
            'cpf'   => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email()
        ];
    }
}
