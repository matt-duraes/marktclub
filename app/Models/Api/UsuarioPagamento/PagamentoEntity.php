<?php

namespace App\Models\Api\UsuarioPagamento;

use ORM\Entity;
use Modules\Data;
use Modules\Dinheiro;
use App\Classes\UsuarioPagamento\Status;
use App\Models\Api\ApiUsuario\UsuarioEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PagamentoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_USUARIO_PAGAMENTO;
    protected array $_buscar = [
        'id_usuario_cliente', 'valor_debito', 'data_pagamento', 'data_cobranca', 'status'
    ];
    protected array $_insert = [
        'id_admin_empresa', 'id_usuario_equipe', 'id_usuario_cliente', 'valor_debito', 'data_cobranca'
    ];
    protected array $_salvar = ['status'];

    public Data $data_pagamento;
    private int $idEquipe;
    private int $idEmpresa;
    private ClienteEntity $Usuario;
    private int $id_usuario_cliente;
    private int $id_usuario_equipe;
    private int $id_admin_empresa;

    public Status $status;

    public function __construct(
        public ?Data $data_cobranca = null,
        public ?Dinheiro $valor_debito = null,
        public ?string $usuario = null
    ) {
        parent::__construct();
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioPagamento\PagamentoEntity');
        }
        $this->setarUsuarioDoPagamento($usuario);
        $this->validarEmpresa();
    }
    private function setarUsuarioDoPagamento(?string $usuario): void
    {
        if (!$usuario) {
            return;
        }

        $Usuario = new ClienteEntity();
        $Usuario->id($usuario, mensagem: 'Usuário enviado não foi encontrado');
        $this->id_usuario_cliente = $Usuario->get('id');
    }

    protected function regraPosBuscar()
    {
        $this->Usuario = new ClienteEntity();
        $this->Usuario->buscar(['id', $this->id_usuario_cliente]);
    }

    protected function regraInsert()
    {
        $this->id_usuario_equipe = $this->idEquipe;
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(1);
    }
}
