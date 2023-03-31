<?php

namespace App\Models\Api\UsuarioIndicacao;

use ORM\Entity;
use Modules\Email;
use Modules\Telefone;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class IndicacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_INDICACAO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome', 'email', 'telefone', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_usuario_cliente', 'id_admin_empresa', 'hash', 'nome', 'email', 'telefone', 'status'
    ];
    protected array $ormUpdate = ['status'];

    public Email $email;
    public Telefone $telefone;
    public array $quem_indicou = [];
    public array $usuario_ativo = [];
    public string $usuario;
    public Status $status;
    protected int $id_admin_empresa;
    protected string $hash;

    protected int $id_usuario_cliente;
    private int $idEmpresa;

    public function __construct()
    {
        parent::__construct();
        $this->setarIdEmpresa();
    }

    protected function regraPosBuscar()
    {
        $this->setarQuemIndicou();
        $this->setarUsuarioAtivado();
    }

    protected function regraInsert()
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->hash = uuid();
        $this->status = new Status('indicado');
        $this->setarUsuarioQueIndicou();
    }

    private function setarUsuarioQueIndicou(): void
    {
        try {
            $Usuario = new ClienteEntity();
            $Usuario->uuid($this->usuario);
            $this->id_usuario_cliente = $Usuario->get('id');
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Usuário enviado não foi encontrado');
        }
    }

    protected function setarUsuarioAtivado()
    {
        if ($this->status->indice() != 'ativado') {
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
            'id' => $Usuario->id,
            'nome' => $Usuario->nome,
            'cpf' => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email()
        ];
    }

    protected function setarQuemIndicou()
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
            'id' => $Usuario->id,
            'nome' => $Usuario->nome,
            'cpf' => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email()
        ];
    }
}
