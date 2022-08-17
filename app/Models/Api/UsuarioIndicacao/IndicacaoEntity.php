<?php

namespace App\Models\Api\UsuarioIndicacao;

use Modules\Email;
use Modules\Telefone;
use App\Models\Api\GeralEntity;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class IndicacaoEntity extends GeralEntity
{
    protected string $_tabela = TABELA_USUARIO_INDICACAO;
    protected array $_buscar = [
        'id_usuario_cliente', 'nome', 'email', 'telefone', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $_insert = ['id_usuario_cliente', 'id_admin_empresa', 'hash', 'nome', 'email', 'telefone', 'status'];
    protected array $_update = ['status'];

    public Email $email;
    public Telefone $telefone;
    public array $quem_indicou = [];
    public array $usuario = [];
    public Status $status;
    public int $id_usuario_cliente;

    public function __construct(
        ?string $usuario = null
    ) {
        $this->setarUsuarioQueIndicou($usuario);
    }

    private function setarUsuarioQueIndicou(?string $usuario): void
    {
        if (!$usuario) {
            return;
        }

        try {
            $Usuario = new ClienteEntity();
            $Usuario->id($usuario);
            $this->id_usuario_cliente = $Usuario->get('id');
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Usuário enviado não foi encontrado');
        }
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

        $this->usuario = [
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
