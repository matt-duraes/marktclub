<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\StatusSaque;
use App\Classes\Silium\TipoConta;
use Helpers\OrmHelper;
use Modules\Cpf;
use Modules\Email;
use Modules\Nome;
use ORM\Entity;

class SiliumSaqueEntity extends Entity
{
    private const PONTUACAO_MINIMA = 10000;

    protected string $ormTabela = TABELA_SILIUM_SAQUE;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome_titular', 'documento_cpf', 'email',
        'tipo_conta', 'banco', 'agencia', 'conta', 'pontuacao', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_usuario_cliente' => '->idUsuario',
        'status' => 1
    ];
    protected array $ormSalvar = [
        'nome_titular', 'documento_cpf', 'email', 'tipo_conta', 'banco',
        'agencia', 'conta', 'pontuacao', 'status'
    ];
    protected string $ormValidarSalvar = '
        nome_titular|Nome do titular|obrigatorio|vazio|valido
        documento_cpf|CPF|obrigatorio|vazio|valido
        email|E-mail|obrigatorio|vazio|valido
        tipo_conta|Tipo de Conta|obrigatorio|vazio|valido
        banco|Instituição Financeira|obrigatorio|vazio
        agencia|Agência|obrigatorio|vazio
        conta|Conta|obrigatorio|vazio
        pontuacao|Pontuação|obrigatorio|vazio
    ';
    protected int $id_usuario_cliente;
    protected int $idUsuario;

    public string|array $usuario;
    public Nome $nome_titular;
    public Cpf $documento_cpf;
    public Email $email;
    public TipoConta $tipo_conta;
    public string $banco;
    public string $agencia;
    public string $conta;
    public int $pontuacao;
    public StatusSaque $status;

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarUsuario();
    }

    protected function regraSalvar(): void
    {
        $this->setarUsuario();
        $this->verificarSolicitacaoPendente();
        $this->validarResgate();
        $this->validarSaldoSuficiente();
    }

    private function setarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $id = $OrmHelper->pegarIdPeloUuid($this->usuario);

        if (empty($id)) {
            mensagemErro(
                'Campo obrigatório!!!',
                'Não foi possível achar um usuário.'
            );
        }
        $this->idUsuario = $id;
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

    private function validarSaldoSuficiente(): void
    {
        if(empty($this->idUsuario)) {
            mensagemErro(
                'Falha na identificação!!!',
                'Houve uma falha e não foi possível identificar o usuário.'
            );
        }

        $OrmHelper = new OrmHelper(TABELA_SILIUM_SALDO);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['id_usuario_cliente', $this->idUsuario],
            ['saldo_silium'],
            'object'
        );
        if (empty($usuario) || ($usuario->saldo_silium < $this->pontuacao)) {
            mensagemErro(
                'Resgate não autorizado!!!',
                'Sua pontuação é insuficiente para o resgate.'
            );
        }
    }

    private function validarResgate(): void
    {
        if ($this->pontuacao < self::PONTUACAO_MINIMA) {
            mensagemErro(
                'Resgate não autorizado!!!',
                'Solicitações de resgate devem ser acima de ' . self::PONTUACAO_MINIMA
            );
        }
    }

    private function verificarSolicitacaoPendente(): void
    {
        $OrmHelper = new OrmHelper($this->ormTabela);
        $saque = $OrmHelper->pegarUltimoRegistro(
            ['id_usuario_cliente', $this->idUsuario],
            ['uuid', 'status'],
            'object'
        );
        $status = (new StatusSaque($saque->status))->indice() === StatusSaque::AGUARDANDO;
        if (!empty($saque->uuid) && $status) {
            mensagemErro(
                'Resgate não autorizado!!!',
                'Você já possui uma solicitação de saque pendente.'
            );
        }
    }
}
