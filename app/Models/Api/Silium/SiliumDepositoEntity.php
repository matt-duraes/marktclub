<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\TipoConta;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Helpers\OrmHelper;
use Modules\Cpf;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

class SiliumDepositoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SILIUM_DEPOSITO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'nome_titular', 'documento_cpf',
        'tipo_conta', 'banco', 'agencia', 'conta', 'valor', 'pontuacao',
        'data_deposito', 'documento_anexo', 'status', 'data_criacao',
        'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'nome_titular', 'documento_cpf', 'tipo_conta', 'banco', 'agencia',
        'conta', 'valor', 'pontuacao', 'data_deposito', 'documento_anexo',
        'status'
    ];
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    public array $empresa;
    public array $usuario;
    public string $nome_titular;
    public Cpf $documento_cpf;
    public TipoConta $tipo_conta;
    public string $banco;
    public string $agencia;
    public string $conta;
    public Dinheiro $valor;
    public int $pontuacao;
    public Data $data_deposito;
    public string $documento_anexo;
    public StatusDeposito $status;

    public function __construct() {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarEmpresa();
        $this->pegarUsuario();
    }

    /*protected function regraSalvar(): void
    {
    }*/

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
}
