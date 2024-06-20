<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\StatusDeposito;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

class SiliumDepositoEntity extends Entity
{
    protected string $ormTabela = TABELA_SILIUM_DEPOSITO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'id_silium_saque', 'valor', 'data_deposito',
        'documento_anexo', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_usuario_cliente', 'id_silium_saque', 'valor', 'data_deposito',
        'documento_anexo', 'status'
    ];
    protected int $id_usuario_cliente;
    protected int $id_silium_saque;

    public string|array $usuario;
    public string|array $saque;
    public Dinheiro $valor;
    public Data $data_deposito;
    public string $documento_anexo;
    public StatusDeposito $status;

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarUsuario();
        $this->pegarSolicitacaoSaque();
    }

    protected function regraSalvar(): void
    {
        $this->setarUsuario();
        $this->setarSolicitacaoSaque();
    }

    private function setarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $id = $OrmHelper->pegarIdPeloUuid($this->usuario);

        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar um usuário.');
        }
        $this->id_usuario_cliente = $id;
    }

    private function setarSolicitacaoSaque(): void
    {
        $OrmHelper = new OrmHelper(TABELA_SILIUM_SAQUE);
        $id = $OrmHelper->pegarIdPeloUuid($this->saque);

        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar a solicitação.');
        }
        $this->id_silium_saque = $id;
    }

    private function pegarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_usuario_cliente],
            ['uuid', 'nome'],
            'object'
        );

        if (empty($usuario->uuid)) {
            $this->usuario = [
                'id'    => '',
                'nome'  => 'Não foi encontrado'
            ];
        }

        $this->usuario = [
            'id'    => $usuario->uuid,
            'nome'  => $usuario->nome
        ];
    }

    private function pegarSolicitacaoSaque(): void
    {
        $OrmHelper = new OrmHelper(TABELA_SILIUM_SAQUE);
        $saque = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_silium_saque],
            [
                'uuid','nome_titular', 'documento_cpf', 'tipo_conta',
                'banco', 'agencia', 'conta', 'pontuacao'
            ],
            'object'
        );

        if (empty($saque->uuid)) {
            $this->saque = [
                'id'            => '',
                'nome_titular'  => '',
                'documento_cpf' => '',
                'tipo_conta'    => '',
                'banco'         => '',
                'agencia'       => '',
                'conta'         => '',
                'pontuacao'     => ''
            ];
        }
        $this->saque = [
            'id'            => $saque->uuid,
            'nome_titular'  => $saque->nome_titular,
            'documento_cpf' => $saque->documento_cpf,
            'tipo_conta'    => $saque->tipo_conta,
            'banco'         => $saque->banco,
            'agencia'       => $saque->agencia,
            'conta'         => $saque->conta,
            'pontuacao'     => $saque->pontuacao
        ];
    }
}
