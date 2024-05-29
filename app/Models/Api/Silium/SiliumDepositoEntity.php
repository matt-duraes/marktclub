<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\Tipo;
use App\Classes\Silium\TipoConta;
use Helpers\OrmHelper;
use Modules\Cpf;
use Modules\Data;
use Modules\Dinheiro;
use Modules\Email;
use ORM\Entity;

class SiliumDepositoEntity extends Entity
{
    private const PONTUACAO_MINIMA = 10000;

    protected string $ormTabela = TABELA_SILIUM_DEPOSITO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'nome_titular',
        'documento_cpf', 'email', 'tipo_conta', 'banco', 'agencia', 'conta',
        'valor', 'pontuacao', 'data_deposito', 'documento_anexo', 'tipo',
        'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'id_usuario_cliente', 'nome_titular', 'documento_cpf',
        'email', 'tipo_conta', 'banco', 'agencia', 'conta', 'valor', 'pontuacao',
        'data_deposito', 'documento_anexo', 'tipo', 'status'
    ];
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;

    public array $empresa;
    public string|array $usuario;
    public string $nome_titular;
    public Cpf $documento_cpf;
    public Email $email;
    public TipoConta $tipo_conta;
    public string $banco;
    public string $agencia;
    public string $conta;
    public Dinheiro $valor;
    public int $pontuacao;
    public Data $data_deposito;
    public string $documento_anexo;
    public Tipo $tipo;
    public StatusDeposito $status;

    public function __construct() {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarEmpresa();
        $this->pegarUsuario();
    }

    protected function regraSalvar(): void
    {
        $this->setarUsuario();
        if ($this->tipo->indice() === Tipo::SAQUE){
            $this->validarResgate();
            $this->validarSaldoSuficiente();
        }
    }

    private function setarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['uuid', $this->usuario],
            ['id', 'id_admin_empresa'],
            'object'
        );

        if (empty($usuario->id)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar um usuário.');
        }

        $this->id_admin_empresa = $usuario->id_admin_empresa;
        $this->id_usuario_cliente = $usuario->id;
    }

    private function pegarEmpresa(): void
    {
        $OrmHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $empresa = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_admin_empresa],
            ['uuid', 'titulo'],
            'object'
            );

        if (empty($empresa->uuid)) {
            $this->empresa = [
                'id'     => '',
                'titulo' => 'Não foi encontrado',
            ];
        }
        $this->empresa = [
            'id'     => $empresa->uuid,
            'titulo' => $empresa->titulo,
        ];
    }

    private function pegarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_usuario_cliente],
            ['uuid', 'nome', 'email_pessoal', 'email_trabalho'],
            'object'
            );

        if (empty($usuario->uuid)) {
            $this->usuario = [
                'id'    => '',
                'nome'  => 'Não foi encontrado',
                'email' => 'Não foi encontrado'
            ];
        }

        $email = 'E-mail não encontrado';
        if (!empty($usuario->email_pessoal)) {
            $email = $usuario->email_pessoal;
        } elseif (!empty($usuario->email_trabalho)) {
            $email = $usuario->email_trabalho;
        }

        $this->usuario = [
            'id'    => $usuario->uuid,
            'nome'  => $usuario->nome,
            'email' => $email
        ];
    }

    private function validarSaldoSuficiente(): void
    {
        $SiliumSaldoEntity = new SiliumSaldoEntity();
        $SiliumSaldoEntity->id($this->id_usuario_cliente);
        if ($SiliumSaldoEntity->saldo < $this->pontuacao) {
            mensagemErro(
                'Resgate não autorizado',
                'Sua pontuação é insuficiente para o resgate!'
            );
        }
    }

    private function validarResgate(): void
    {
        if ($this->pontuacao < self::PONTUACAO_MINIMA) {
            mensagemErro(
                'Resgate não autorizado',
                'Solicitações devem ser acima de ' . self::PONTUACAO_MINIMA
            );
        }
    }
}
